<?php

declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

/**
 * @license GNU GPL v2+
 * @author Gabriel Birke < gabriel.birke@wikimedia.de >
 */
class OtrsConnector {

	/**
	 * @var Client
	 */
	private $client;
	private $url;
	private $username;
	private $password;

	public function __construct( Client $client, string $url, string $username, string $password ) {
		$this->client = $client;
		$this->url = $url;
		$this->username = $username;
		$this->password = $password;
	}

	public function setTicketOwner( string $ticketNumber, int $newOwnerId ): array {
		$response = $this->client->post( $this->url, [
			'body' => $this->createRequestBody( $ticketNumber, $newOwnerId ),
			'headers' => [
				'Content-Type' => 'application/json'
			]
		] );
		return $this->extractResponseData( $response );
	}

	private function createRequestBody( $ticketNumber, $newOwnerId ): string {
		return json_encode( [
			'UserLogin' => $this->username,
			'Password' => $this->password,
			'TicketNumber' => $ticketNumber,
			'Ticket' => [
				'OwnerID' => $newOwnerId
			]
		] );
	}

	private function extractResponseData( ResponseInterface $response ): array {
		if ( $response->getStatusCode() !== 200 ) {
			throw new OtrsConnectorException( 'Connection error, HTTP Status Code ' . $response->getStatusCode() );
		}
		$body = $response->getBody()->getContents();
		$responseData = json_decode( $body, true );
		if ( !is_array( $responseData ) ) {
			throw new OtrsConnectorException( "Could not decode JSON response: \n" . $response->getBody() );
		}
		if ( array_key_exists( 'Error', $responseData ) ) {
			throw new OtrsConnectorException( $responseData['Error']['ErrorMessage'] );
		}
		return $responseData;
	}

}