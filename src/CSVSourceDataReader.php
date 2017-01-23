<?php

declare(strict_types = 1);

namespace WMDE\OtrsExtractAddress;

/**
 * @license GNU GPL v2+
 * @author Gabriel Birke < gabriel.birke@wikimedia.de >
 */
class CSVSourceDataReader implements SourceDataReader {

	private const COL_TICKET_NUMBER = 0;
	private const COL_EMAIL = 15;
	private const COL_BODY = 23;

	private $sourceStream;
	private $delimiter;
	private $enclosure;

	public function __construct( $sourceStream, string $delimiter, string $enclosure ) {
		$this->sourceStream = $sourceStream;
		$this->delimiter = $delimiter;
		$this->enclosure = $enclosure;
	}

	public function hasMoreRows(): bool {
		return !feof( $this->sourceStream );
	}

	public function getRow(): SourceData {
		$row = fgetcsv( $this->sourceStream, null, $this->delimiter, $this->enclosure );
		return new SourceData(
			(int) $row[self::COL_TICKET_NUMBER ],
			$row[ self::COL_EMAIL ],
			$row[ self::COL_BODY ]
		);
	}

}