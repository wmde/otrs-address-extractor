<?php

declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress\Cli;

use Dotenv\Dotenv;
use GuzzleHttp\Client;
use WMDE\OtrsExtractAddress\OtrsConnector;

/**
 * @license GNU GPL v2+
 * @author Gabriel Birke < gabriel.birke@wikimedia.de >
 */
class OtrsConnectorFactory {
	
	public static function createConnectorFromEnvironment(): OtrsConnector {
		$dotenv = new Dotenv( getcwd() );
		$dotenv->load();
		$url = getenv( 'OTRS_URL' );
		$user = getenv( 'OTRS_USER' );
		$password = getenv( 'OTRS_PASSWORD' );
		if ( !$url || !$user || !$password ) {
			throw new \RuntimeException( 'You must set OTRS_URL, OTRS_USER and OTRS_PASSWORD in your environment!' );
		}
		return new OtrsConnector( new Client(), $url, $user, $password );
	}

}