<?php

declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress;

use GuzzleHttp\Client;

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

	public function setTicketOwner( string $ticketNumber, int $newOwnerId ) {
		$this->client->post( $this->url, [
			'UserLogin' => $this->username,
			'Password' => $this->password,
			'TicketID' => $ticketNumber,
			'Ticket' => [
				'OwnerID' => $newOwnerId
			]
		] );
	}

}