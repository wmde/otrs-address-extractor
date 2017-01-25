<?php

declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress\UseCases\ExtractAddress;

use WMDE\OtrsExtractAddress\Domain\Address;
use WMDE\OtrsExtractAddress\Domain\UniqueId;

class ExtractedData
{
	private $ticketNumber;
	private $email;
	private $title;
	private $address;
	private $uniqueId;

	public function __construct( int $ticketNumber, string $email, string $title, Address $address, UniqueId $uniqueId )
	{
		$this->ticketNumber = $ticketNumber;
		$this->email = $email;
		$this->title = $title;
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