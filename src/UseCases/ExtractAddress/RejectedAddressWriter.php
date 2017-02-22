<?php

declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress\UseCases\ExtractAddress;

use WMDE\OtrsExtractAddress\Domain\SourceData;

/**
 * @license GNU GPL v2+
 * @author Gabriel Birke < gabriel.birke@wikimedia.de >
 */
class RejectedAddressWriter {

	private $outputStream;

	public function __construct( \SplFileObject $outputStream ) {
		$this->outputStream = $outputStream;
		$this->csvFormatter = new CSVFormatter();
	}

	public function writeRow( SourceData $data, string $errorMsg ) {
		// TODO add option to skip "not found" messages and export only other messages
		$this->outputStream->fwrite( $this->csvFormatter->formatAsCSV( [
			$data->getTicketNumber(),
			$data->getEmail(),
			$data->getTitle(),
			$errorMsg,
			$data->getBody()
		] ) );
	}
}