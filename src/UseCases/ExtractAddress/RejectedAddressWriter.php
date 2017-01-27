<?php

declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress\UseCases\ExtractAddress;

use WMDE\OtrsExtractAddress\Domain\SourceData;

/**
 * @license GNU GPL v2+
 * @author Gabriel Birke < gabriel.birke@wikimedia.de >
 */
class RejectedAddressWriter {

	use CSVFormatter;

	private $outputStream;

	public function __construct( $outputStream ) {
		$this->outputStream = $outputStream;
	}

	public function writeRow( SourceData $data, string $errorMsg ) {
		// TODO add option to skip "not found" messages and export only other messages
		fwrite( $this->outputStream, $this->formatAsCSV( [
			$data->getTicketNumber(),
			$data->getEmail(),
			$data->getTitle(),
			$errorMsg,
			$data->getBody()
		] ) );
	}
}