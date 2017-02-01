<?php

declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress\Test\Unit;

use GuzzleHttp\Client;
use WMDE\OtrsExtractAddress\OtrsConnector;

class OtrsConnectorTest extends \PHPUnit_Framework_TestCase {

	const NEW_OWNER_ID = 99;
	const TICKET_NUMBER = '123';

	public function testApiCallIsMadeToUrl() {
		$client = $this->getMockBuilder( Client::class )->setMethods( [ 'request' ] )->getMock();
		$client->expects( $this->once() )
			->method( 'request' )
			->with(
				$this->anything(),
				$this->matches( 'https://example.com' ),
				$this->anything()
			);
		$connector = new OtrsConnector( $client, 'https://example.com', 'George Washington', '1d3p3nd3nc3' );
		$connector->setTicketOwner( self::TICKET_NUMBER, self::NEW_OWNER_ID );
	}

	public function testApiCallIsMadeWithCredentials() {
		$client = $this->getMockBuilder( Client::class )->setMethods( [ 'request' ] )->getMock();
		$client->expects( $this->once() )
			->method( 'request' )
			->with(
				$this->anything(),
				$this->anything(),
				$this->callback( function ( array $data ) {
					$this->assertArrayHasKey( 'body', $data );
					$this->assertArraySubset( [
						'UserLogin' => 'George Washington',
						'Password' => '1d3p3nd3nc3'
					], $data['body'] );
					return true;
				} )
			);
		$connector = new OtrsConnector( $client, 'https://example.com', 'George Washington', '1d3p3nd3nc3' );
		$connector->setTicketOwner( self::TICKET_NUMBER, self::NEW_OWNER_ID );
	}

	public function testNewOwnerIdIsSet() {
		$client = $this->getMockBuilder( Client::class )->setMethods( [ 'request' ] )->getMock();
		$client->expects( $this->once() )
			->method( 'request' )
			->with(
				$this->anything(),
				$this->anything(),
				$this->callback( function ( array $data ) {
					$this->assertArrayHasKey( 'body', $data );
					$this->assertArraySubset( [
						'TicketID' => self::TICKET_NUMBER,
						'Ticket' => [ 'OwnerID' => self::NEW_OWNER_ID ]
					], $data['body'] );
					return true;
				} )
			);
		$connector = new OtrsConnector( $client, 'https://example.com', 'George Washington', '1d3p3nd3nc3' );
		$connector->setTicketOwner( self::TICKET_NUMBER, self::NEW_OWNER_ID );
	}

	public function testContentTypeIsApplicationJson() {
		$client = $this->getMockBuilder( Client::class )->setMethods( [ 'request' ] )->getMock();
		$client->expects( $this->once() )
			->method( 'request' )
			->with(
				$this->anything(),
				$this->anything(),
				$this->callback( function ( array $data ) {
					$this->assertArrayHasKey( 'headers', $data );
					$this->assertArraySubset( [
						'Content-Type' => 'application/json'
					], $data['headers'] );
					return true;
				} )
			);
		$connector = new OtrsConnector( $client, 'https://example.com', 'George Washington', '1d3p3nd3nc3' );
		$connector->setTicketOwner( self::TICKET_NUMBER, self::NEW_OWNER_ID );
	}

}
