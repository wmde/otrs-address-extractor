<?php

declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress\UseCases\ExtractAddress;

use WMDE\OtrsExtractAddress\DataAccess\SourceDataReader;
use WMDE\OtrsExtractAddress\Domain\SourceData;
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

	public function extractAddresses( SourceDataReader $reader, $outputStream, $rejectionStream ) {
		while ( $reader->hasMoreRows() ) {
			$data = $reader->getRow();
			$result = $this->validator->validate( $data );
			if ( $result->isValid() ) {
				fwrite( $outputStream, $this->formatAsCSV( $this->createOutputRow( $result->getExtractedData() ) ) );
			} else {
				fwrite( $rejectionStream, $this->formatAsCSV( $this->createRejectionRow( $data, $result->getValidationError() ) ) );
			}
		}
	}

	private function createOutputRow( ExtractedData $data ) {
		return [
			$data->getTicketNumber(),
			$data->getEmail(),
			$data->getUniqueId()->getType(),
			$data->getUniqueId()->getId(),
			$data->getAddress()->getStreet(),
			$data->getAddress()->getPostcode(),
			$data->getAddress()->getCity()
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
				return '"'. str_replace( '"', '""', $v ) . '"';
			} else {
				return '';
			}
		}, $row ) ) . "\n";
	}
}