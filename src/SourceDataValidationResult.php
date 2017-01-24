<?php

declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress;

/**
 * @license GNU GPL v2+
 * @author Gabriel Birke < gabriel.birke@wikimedia.de >
 */
class SourceDataValidationResult {

	const ERR_NO_ADDRESS = 'No address found.';
	const ERR_TOO_MANY_ADDRESSES = 'Several addresses found.';
	const ERR_NO_UNIQUE_ID = 'No address or membership id found.';

	private $validationError;
	private $address;

	public function __construct( Address $address = null, string $validationError ) {
		$this->validationError = $validationError;
		$this->address = $address;
	}

	public static function newValidResult( Address $address ) {
		return new self( $address, '' );
	}

	public static function newInvalidResult( string $validationError ) {
		return new self( null, $validationError );
	}

	public function getValidationError(): string {
		return $this->validationError;
	}

	public function isValid(): bool {
		return $this->getValidationError() === '';
	}

	public function getAddress(): Address {
		return $this->address;
	}
}