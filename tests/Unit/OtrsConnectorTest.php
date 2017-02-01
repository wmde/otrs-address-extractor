<?php

declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress\Test\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use WMDE\OtrsExtractAddress\OtrsConnector;
use WMDE\OtrsExtractAddress\OtrsConnectorException;

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
			)
			->willReturn( $this->createEmptyResponse() );
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
					$this->assertArraySubset(
						[
							'UserLogin' => 'George Washington',
							'Password' => '1d3p3nd3nc3'
						],
						json_decode( $data['body'], true )
					);
					return true;
				} )
			)
			->willReturn( $this->createEmptyResponse() );
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
					$this->assertArraySubset(
						[
						'TicketNumber' => self::TICKET_NUMBER,
						'Ticket' => [ 'OwnerID' => self::NEW_OWNER_ID ]
						],
						json_decode( $data['body'], true )
					);
					return true;
				} )
			)
			->willReturn( $this->createEmptyResponse() );
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
			)
			->willReturn( $this->createEmptyResponse() );
		$connector = new OtrsConnector( $client, 'https://example.com', 'George Washington', '1d3p3nd3nc3' );
		$connector->setTicketOwner( self::TICKET_NUMBER, self::NEW_OWNER_ID );
	}

	public function testNonOkStatusCodeThrowsException() {
		$this->expectException( OtrsConnectorException::class );
		$client = $this->getMockBuilder( Client::class )->setMethods( [ 'request' ] )->getMock();
		$client->expects( $this->once() )
			->method( 'request' )
			->with(
				$this->anything(),
				$this->anything(),
				$this->anything()
			)
			->willReturn( new Response( 404, [], 'Not found' ) );
		$connector = new OtrsConnector( $client, 'https://example.com', 'George Washington', '1d3p3nd3nc3' );
		$connector->setTicketOwner( self::TICKET_NUMBER, self::NEW_OWNER_ID );
	}

	public function testMalformedJsonResponseThrowsException() {
		$this->expectException( OtrsConnectorException::class );
		$client = $this->getMockBuilder( Client::class )->setMethods( [ 'request' ] )->getMock();
		$client->expects( $this->once() )
			->method( 'request' )
			->with(
				$this->anything(),
				$this->anything(),
				$this->anything()
			)
			->willReturn( new Response( 200, [], 'xxx' ) );
		$connector = new OtrsConnector( $client, 'https://example.com', 'George Washington', '1d3p3nd3nc3' );
		$connector->setTicketOwner( self::TICKET_NUMBER, self::NEW_OWNER_ID );
	}

	public function testErrorInJsonResponseThrowsException() {
		$this->expectException( OtrsConnectorException::class );
		$this->expectExceptionMessageRegExp( '/TicketUpdate: User does/' );
		$client = $this->getMockBuilder( Client::class )->setMethods( [ 'request' ] )->getMock();
		$client->expects( $this->once() )
			->method( 'request' )
			->with(
				$this->anything(),
				$this->anything(),
				$this->anything()
			)
			->willReturn( new Response( 200, [], '{"Error":{"ErrorCode":"TicketUpdate.AccessDenied","ErrorMessage":"TicketUpdate: User does not have access to the ticket!"}}' ) );
		$connector = new OtrsConnector( $client, 'https://example.com', 'George Washington', '1d3p3nd3nc3' );
		$connector->setTicketOwner( self::TICKET_NUMBER, self::NEW_OWNER_ID );
	}

	public function testSuccessREsponseIsReturned() {
		$client = $this->getMockBuilder( Client::class )->setMethods( [ 'request' ] )->getMock();
		$client->expects( $this->once() )
			->method( 'request' )
			->with(
				$this->anything(),
				$this->anything(),
				$this->anything()
			)
			->willReturn( new Response( 200, [], '{"ArticleID":"124527","TicketNumber":"2017012779000491","TicketID":1146062}' ) );
		$connector = new OtrsConnector( $client, 'https://example.com', 'George Washington', '1d3p3nd3nc3' );
		$this->assertSame(
			[
				'ArticleID' => '124527',
				'TicketNumber' => '2017012779000491',
				'TicketID' => 1146062
			],
			$connector->setTicketOwner( self::TICKET_NUMBER, self::NEW_OWNER_ID )
		);
	}

	private function createEmptyResponse(): Response {
		return new Response( 200, [], '{}' );
	}

}
