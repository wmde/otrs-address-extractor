<?php

declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress\UseCases\ExtractAddress;

/**
 * @license GNU GPL v2+
 * @author Gabriel Birke < gabriel.birke@wikimedia.de >
 */
class FoundAddressWriter {

	private $outputStream;
	private $linkTemplate;
	private $csvFormatter;

	public function __construct( \SplFileObject $outputStream, string $linkTemplate ) {
		$this->outputStream = $outputStream;
		$this->linkTemplate = $linkTemplate;
		$this->csvFormatter = new CSVFormatter();
	}

	public function writeRow( ExtractedData $data ) {
		$this->outputStream->fwrite( $this->csvFormatter->formatAsCSV( [
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