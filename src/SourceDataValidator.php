<?php

declare( strict_types = 1 );

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
		$addresses = $this->addressFilter->getValidAddresses( $data->getAddresses() );
		switch ( count( $addresses ) ) {
			case 0:
				return SourceDataValidationResult::newInvalidResult( SourceDataValidationResult::ERR_NO_ADDRESS );
			case 1:
				break;
			default:
				return SourceDataValidationResult::newInvalidResult( SourceDataValidationResult::ERR_TOO_MANY_ADDRESSES );
		}
		$address = $this->addressFilter->firstValidAddress( $addresses );
		$uniqueIds = $data->getUniqueIds();
		$uniqueIds = array_filter( $uniqueIds, function( UniqueId $id ) use ( $address ) {
			return (string) $id->getId() !== $address->getPostcode();
		} );
		if ( count( $uniqueIds ) === 0 ) {
			return SourceDataValidationResult::newInvalidResult( SourceDataValidationResult::ERR_NO_UNIQUE_ID );
		}
		return SourceDataValidationResult::newValidResult( new ExtractedData(
			$data->getTicketNumber(),
			$data->getEmail(),
			$address,
			array_shift( $uniqueIds )
		) );
	}
}