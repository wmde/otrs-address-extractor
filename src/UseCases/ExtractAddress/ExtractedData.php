<?php

declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress\UseCases\ExtractAddress;

use WMDE\OtrsExtractAddress\Domain\Address;
use WMDE\OtrsExtractAddress\Domain\UniqueId;

class ExtractedData
{
	private $ticketId;
	private $ticketNumber;
	private $email;
	private $title;
	private $address;
	private $uniqueId;

	public function __construct( int $ticketId, int $ticketNumber, string $email, string $title, Address $address, UniqueId $uniqueId )
	{
		$this->ticketId = $ticketId;
		$this->ticketNumber = $ticketNumber;
		$this->email = $email;
		$this->title = $title;
		$this->address = $address;
		$this->uniqueId = $uniqueId;
	}

	public function getTicketId(): int {
		return $this->ticketId;
	}

	public function getTicketNumber(): int
	{
		return $this->ticketNumber;
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	public function getTitle(): string {
		return $this->title;
	}

	public function getAddress(): Address
	{
		return $this->address;
	}

	public function getUniqueId(): UniqueId
	{
		return $this->uniqueId;
	}

}