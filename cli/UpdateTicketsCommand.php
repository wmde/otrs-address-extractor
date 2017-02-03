<?php
declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress\Cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;
use WMDE\OtrsExtractAddress\DataAccess\TicketNumberReader;
use WMDE\OtrsExtractAddress\UseCases\UpdateTickets\UpdateTicketsUseCase;

/**
 * @license GNU GPL v2+
 * @author Gabriel Birke < gabriel.birke@wikimedia.de >
 */
class UpdateTicketsCommand extends Command {

	/**
	 * @var UpdateTicketsUseCase
	 */
	private $updateTicketsUseCase;

	/**
	 * UpdateTicketsCommand constructor.
	 * @param UpdateTicketsUseCase $updateTicketsUseCase
	 */
	public function __construct( UpdateTicketsUseCase $updateTicketsUseCase ) {
		parent::__construct();
		$this->updateTicketsUseCase = $updateTicketsUseCase;
	}

	protected function configure()
	{
		$this
			->setName( 'update-tickets' )
			->setDescription( 'Update tickets with new owner' )
			->setHelp( 'You must have the variables OTRS_URL, OTRS_USER and OTRS_PASSWORD set in the environment or an .env file' )
			->addOption( 'owner', 'i', InputOption::VALUE_REQUIRED, 'OTRS Owner id', 27 )
			->addArgument( 'inputfile', InputArgument::REQUIRED, 'CSV file with ticket numbers in the first column' );
	}

	protected function execute( InputInterface $input, OutputInterface $output )
	{
		$inputFile = new TicketNumberReader( $input->getArgument( 'inputfile' ) );
		$this->updateTicketsUseCase->setLogger( new ConsoleLogger( $output ) );
		$updatedTickets = $this->updateTicketsUseCase->updateTickets( $inputFile, (int) $input->getOption( 'owner' ) );
		if ( $output->isVerbose() ) {
			$output->writeln( sprintf( 'Updated %d tickets.', $updatedTickets) );
		}
	}

}