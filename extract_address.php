<?php

require_once( __DIR__ . '/vendor/autoload.php' );

use Symfony\Component\Console\Application;
use WMDE\OtrsExtractAddress\Address;
use WMDE\OtrsExtractAddress\AddressExtractor;
use WMDE\OtrsExtractAddress\AddressFilter;
use WMDE\OtrsExtractAddress\ExtractAddressCommand;
use WMDE\OtrsExtractAddress\SourceDataValidator;

$filter = new AddressFilter( [ new Address( 'Tempelhofer Ufer 23-24', '10963', 'Berlin') ] );

$cmd = new ExtractAddressCommand( new AddressExtractor( new SourceDataValidator( $filter ) ) );

$application = new Application();
$application->add( $cmd );
$application->setDefaultCommand( $cmd->getName() );

$application->run();
