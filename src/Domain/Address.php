<?php

declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress\Domain;

/**
 * Value object for address extraction
 *
 * @license GNU GPL v2+
 * @author Gabriel Birke < gabriel.birke@wikimedia.de >
 */
class Address {

	private $street;
	private $postcode;
	private $city;

	public function __construct( string $street, string $postcode, string $city ) {
		$this->street = $street;
		$this->postcode = $postcode;
		$this->city = $city;
	}

	public function getStreet(): string {
		return $this->street;
	}

	public function getPostcode(): string {
		return $this->postcode;
	}

	public function getCity(): string {
		return $this->city;
	}

	public function isValid(): bool {
		return strlen( $this->getStreet() ) > 3 &&
			$this->getPostcode() !== '' &&
			strlen( $this->getCity() ) > 1;
	}

	public function getHash(): string
	{
		return md5( $this->getStreet() . $this->getPostcode() . $this->getCity() );
	}

}