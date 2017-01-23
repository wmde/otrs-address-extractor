<?php

declare(strict_types = 1);

namespace WMDE\OtrsExtractAddress;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @license GNU GPL v2+
 * @author Gabriel Birke < gabriel.birke@wikimedia.de >
 */
class ExtractAddressCommand extends Command {

	/**
	 * @var AddressExtractor
	 */
	private $addressExtractor;

	protected function configure()
	{
		$this
			->setName('extract')
			->setDescription('Extract addresses from OTRS file')
			->addOption( 'output', 'o', InputOption::VALUE_REQUIRED, 'Output file' )
			->addOption( 'rejected', 'r', InputOption::VALUE_REQUIRED, 'Filename for rejected entries' )
			->addArgument( 'inputfile', InputArgument::REQUIRED )
		;
	}

	protected function execute( InputInterface $input, OutputInterface $output )
	{
		$outputStream = $input->getOption( 'output' ) ?: STDOUT;
		$rejectStream = $input->getOption( 'rejected' ) ?: STDERR;
		$inputStream = fopen( $input->getArgument( 'inputfile' ), 'r' );
		$this->addressExtractor->extractAddresses( $inputStream, $outputStream, $rejectStream );
	}

}