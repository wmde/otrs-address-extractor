<?php

declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress\Domain;

/**
 * @license GNU GPL v2+
 * @author Gabriel Birke < gabriel.birke@wikimedia.de >
 */
class SourceData {

	private $ticketNumber;
	private $email;
	private $body;
	private $addressFinder;
	private $uniqueIdFinder;

	public function __construct( int $ticketNumber, string $email, string $body ) {
		$this->ticketNumber = $ticketNumber;
		$this->email = $email;
		$this->body = $body;
		$this->addressFinder = new AddressFinder();
		$this->uniqueIdFinder = new UniqueIdFinder();
	}

	public function getTicketNumber(): int {
		return $this->ticketNumber;
	}

	public function getEmail(): string {
		return $this->email;
	}

	public function getBody(): string {
		return $this->body;
	}

	public function getAddresses(): array {
		return $this->addressFinder->findAddresses( $this->getBody() );
	}

	public function getUniqueIds(): array {
		return $this->uniqueIdFinder->findIds( $this->getBody() );
	}
}