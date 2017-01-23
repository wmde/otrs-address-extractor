<?php

require_once( __DIR__ . '/vendor/autoload.php' );

use Symfony\Component\Console\Application;
use WMDE\OtrsExtractAddress\AddressExtractor;
use WMDE\OtrsExtractAddress\ExtractAddressCommand;

$cmd = new ExtractAddressCommand( new AddressExtractor() );

$application = new Application();
$application->add( $cmd );
$application->setDefaultCommand( $cmd->getName() );

$application->run();
