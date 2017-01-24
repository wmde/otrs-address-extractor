<?php

declare(strict_types = 1);

namespace WMDE\OtrsExtractAddress;

/**
 * @license GNU GPL v2+
 * @author Gabriel Birke < gabriel.birke@wikimedia.de >
 */
class AddressExtractor {

	private $validator;

	public function __construct( SourceDataValidatorInterface $validator ) {
		$this->validator = $validator;
	}

	public function extractAddresses( SourceDataReader $reader, $outputStream, $rejectionStream ) {

		while( $reader->hasMoreRows() ) {
			$data = $reader->getRow();
			$result = $this->validator->validate( $data );
			if( $result->isValid() ) {
				fwrite( $outputStream, $this->formatAsCSV( $this->createOutputRow( $data, $result->getAddress() ) ) );
			} else {
				fwrite( $rejectionStream, $this->formatAsCSV( $this->createRejectionRow( $data, $result->getValidationError() ) ) );
			}
		}
	}

	private function createOutputRow( SourceData $data, Address $address ) {
		return [
			$data->getTicketNumber(),
			$data->getEmail(),
			$data->getUniqueIds()[0]->getType(),
			$data->getUniqueIds()[0]->getId(),
			$address->getStreet(),
			$address->getPostcode(),
			$address->getCity()
		];
	}

	private function createRejectionRow( SourceData $data, string $errorMsg ) {
		return [
			$data->getTicketNumber(),
			$data->getEmail(),
			$errorMsg,
			$data->getBody()
		];
	}

	private function formatAsCSV( array $row ): string {
		return implode( ';', array_map( function( $v ) {
			if ( $v ) {
				return '"'. str_replace( '"', '\\"', $v ) . '"';
			} else {
				return '';
			}
		}, $row ) ) . "\n";
	}
}