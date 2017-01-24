<?php

declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress;

class ExtractedData
{
	private $ticketNumber;
	private $email;
	private $address;
	private $uniqueId;

	public function __construct( int $ticketNumber, string $email, Address $address, UniqueId $uniqueId )
	{
		$this->ticketNumber = $ticketNumber;
		$this->email = $email;
		$this->address = $address;
		$this->uniqueId = $uniqueId;
	}

	public function getTicketNumber(): int
	{
		return $this->ticketNumber;
	}

	public function getEmail(): string
	{
		return $this->email;
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