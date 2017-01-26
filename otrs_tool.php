<?php

require_once( __DIR__ . '/vendor/autoload.php' );

use Symfony\Component\Console\Application;
use WMDE\OtrsExtractAddress\Cli\ExtractAddressFromDbCommand;
use WMDE\OtrsExtractAddress\Domain\Address;
use WMDE\OtrsExtractAddress\UseCases\ExtractAddress\ExtractAddressUseCase;
use WMDE\OtrsExtractAddress\AddressFilter;
use WMDE\OtrsExtractAddress\Cli\ExtractAddressFromCsvCommand;
use WMDE\OtrsExtractAddress\SourceDataValidator;

$filter = new AddressFilter( [ new Address( 'Tempelhofer Ufer 23-24', '10963', 'Berlin') ] );
$extractAddressUseCase = new ExtractAddressUseCase( new SourceDataValidator( $filter ) );

$application = new Application();
$application->add( new ExtractAddressFromCsvCommand( $extractAddressUseCase ) );
$application->add( new ExtractAddressFromDbCommand( $extractAddressUseCase ) );

$application->run();
