<?php

declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress\Cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use WMDE\OtrsExtractAddress\DataAccess\CSVSourceDataReader;
use WMDE\OtrsExtractAddress\UseCases\ExtractAddress\ExtractAddressUseCase;

/**
 * @license GNU GPL v2+
 * @author Gabriel Birke < gabriel.birke@wikimedia.de >
 */
class ExtractAddressCommand extends Command {

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
			->setName( 'extract' )
			->setDescription( 'Extract addresses from OTRS file' )
			->addOption( 'output', 'o', InputOption::VALUE_REQUIRED, 'Output file' )
			->addOption( 'rejected', 'r', InputOption::VALUE_REQUIRED, 'Filename for rejected entries' )
			->addArgument( 'inputfile', InputArgument::REQUIRED );
	}

	protected function execute( InputInterface $input, OutputInterface $output )
	{
		$outputStream = $input->getOption( 'output' ) ? fopen( $input->getOption( 'output' ), 'w' ) : STDOUT;
		$rejectStream = $input->getOption( 'rejected' ) ? fopen( $input->getOption( 'rejected' ), 'w' ) : STDERR;
		$reader = new CSVSourceDataReader( fopen( $input->getArgument( 'inputfile' ), 'r' ), ';', '"' );
		$this->addressExtractor->extractAddresses( $reader, $outputStream, $rejectStream );
	}

}