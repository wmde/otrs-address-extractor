<?php

declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress\UseCases\ExtractAddress;

/**
 * @license GNU GPL v2+
 * @author Gabriel Birke < gabriel.birke@wikimedia.de >
 */
class FoundAddressWriter {

	use CSVFormatter;

	private $outputStream;
	private $linkTemplate;

	public function __construct( $outputStream, string $linkTemplate ) {
		$this->outputStream = $outputStream;
		$this->linkTemplate = $linkTemplate;
	}

	public function writeRow( ExtractedData $data ) {
		fwrite( $this->outputStream, $this->formatAsCSV( [
			$data->getTicketNumber(),
			$data->getEmail(),
			$data->getTitle(),
			$data->getUniqueId()->getType(),
			$data->getUniqueId()->getId(),
			$data->getAddress()->getStreet(),
			$data->getAddress()->getPostcode(),
			$data->getAddress()->getCity(),
			sprintf( $this->linkTemplate, $data->getTicketId() )
		] ) );
	}

}