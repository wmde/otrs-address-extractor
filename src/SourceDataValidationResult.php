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
	private $extractedData;

	public function __construct( ExtractedData $extractedData = null, string $validationError ) {
		$this->validationError = $validationError;
		$this->extractedData = $extractedData;
	}

	public static function newValidResult( ExtractedData $extractedData ) {
		return new self( $extractedData, '' );
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

	public function getExtractedData(): ExtractedData {
		return $this->extractedData;
	}
}