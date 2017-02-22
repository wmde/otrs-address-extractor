<?php

declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress\Cli;

use Dotenv\Dotenv;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use WMDE\OtrsExtractAddress\DataAccess\CSVSourceDataReader;
use WMDE\OtrsExtractAddress\DataAccess\DbSourceDataReader;
use WMDE\OtrsExtractAddress\UseCases\ExtractAddress\ExtractAddressUseCase;
use WMDE\OtrsExtractAddress\UseCases\ExtractAddress\FoundAddressWriter;
use WMDE\OtrsExtractAddress\UseCases\ExtractAddress\RejectedAddressWriter;

/**
 * @license GNU GPL v2+
 * @author Gabriel Birke < gabriel.birke@wikimedia.de >
 */
class ExtractAddressFromDbCommand extends Command {

	/**
	 * @var ExtractAddressUseCase
	 */
	private $addressExtractor;

	public function __construct( ExtractAddressUseCase $addressExtractor )
	{
		parent::__construct();
		$this->addressExtractor = $addressExtractor;
	}

	protected function configure()
	{
		$this
			->setName( 'extract:db' )
			->setDescription( 'Extract addresses from OTRS database' )
			->setHelp( 'You must have the variables DB_DSN, DB_USER and DB_PASSWORD set in the environment or an .env file' )
			->addOption( 'output', 'o', InputOption::VALUE_REQUIRED, 'Output file' )
			->addOption( 'rejected', 'r', InputOption::VALUE_REQUIRED, 'Filename for rejected entries' )
			->addOption( 'link-template', null, InputOption::VALUE_REQUIRED, 'Ticket IDS will be inserted into this template', '%d' )
			->addOption( 'start-time', null, InputOption::VALUE_REQUIRED, 'Start exporting from this point in time (YYYY-MM-DD HH:MM:SS), time can be omitted', '2017-01-19' )
			->addOption( 'end-time', null, InputOption::VALUE_REQUIRED, 'End exporting at this point in time (YYYY-MM-DD HH:MM:SS), time can be omitted', 'now' );

	}

	protected function execute( InputInterface $input, OutputInterface $output )
	{
		$startTime = new \DateTime( $input->getOption( 'start-time' )  );
		$endTime = new \DateTime( $input->getOption( 'end-time' ) );

		$outputStreamName = $input->getOption( 'output' ) ?? 'php://stdout';
		$rejectStreamName = $input->getOption( 'rejected' ) ??  'php://stderr';
		$outputStream = new \SplFileObject( $outputStreamName, 'w' );
		$rejectStream = new \SplFileObject( $rejectStreamName, 'w' );

		try {
			$reader = new DbSourceDataReader( $this->getDb(), $startTime, $endTime );
		} catch ( \RuntimeException $e ) {
			$output->writeln( "<error>{$e->getMessage()}</error>" );
			return;
		}

		$this->addressExtractor->extractAddresses(
			$reader,
			new FoundAddressWriter( $outputStream, $input->getOption( 'link-template') ),
			new RejectedAddressWriter( $rejectStream )
		);
	}

	private function getDb(): \PDO {
		$dotenv = new Dotenv( getcwd() );
		$dotenv->load();
		$dsn = getenv( 'DB_DSN' );
		$user = getenv( 'DB_USER' );
		$password = getenv( 'DB_PASSWORD' );
		if ( !$dsn || !$user || !$password ) {
			throw new \RuntimeException( 'You must set DB_DSN, DB_USER and DB_PASSWORD in your environment!' );
		}
		return new \PDO( $dsn, $user, $password, [ \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8' ] );
	}

}