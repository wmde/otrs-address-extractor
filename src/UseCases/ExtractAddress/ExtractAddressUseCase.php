<?php

declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress\UseCases\ExtractAddress;

use WMDE\OtrsExtractAddress\DataAccess\SourceDataReader;
use WMDE\OtrsExtractAddress\SourceDataValidatorInterface;

/**
 * @license GNU GPL v2+
 * @author Gabriel Birke < gabriel.birke@wikimedia.de >
 */
class ExtractAddressUseCase {

	private $validator;

	public function __construct( SourceDataValidatorInterface $validator ) {
		$this->validator = $validator;
	}

	public function extractAddresses( SourceDataReader $reader, FoundAddressWriter $output, RejectedAddressWriter $rejected ) {
		while ( $reader->hasMoreRows() ) {
			$data = $reader->getRow();
			$result = $this->validator->validate( $data );
			if ( $result->isValid() ) {
				 $output->writeRow( $result->getExtractedData() );
			} else {
				 $rejected->writeRow( $data, $result->getValidationError() );
			}
		}
	}
}