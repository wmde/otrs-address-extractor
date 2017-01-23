<?php

declare(strict_types = 1);

namespace WMDE\OtrsExtractAddress;

/**
 * @license GNU GPL v2+
 * @author Gabriel Birke < gabriel.birke@wikimedia.de >
 */
class SourceDataValidator implements SourceDataValidatorInterface {

	private $addressFilter;

	public function __construct( AddressFilter $addressFilter ) {
		$this->addressFilter = $addressFilter;
	}

	public function validate( SourceData $data ): SourceDataValidationResult {
		// TODO proper validation criteria
		return SourceDataValidationResult::newInvalidResult( 'Not implemented' );
	}
}