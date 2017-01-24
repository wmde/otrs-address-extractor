<?php

declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress\Test\Fixtures;

use WMDE\OtrsExtractAddress\SourceData;
use WMDE\OtrsExtractAddress\SourceDataReader;

/**
 * @license GNU GPL v2+
 * @author Gabriel Birke < gabriel.birke@wikimedia.de >
 */
class FileSourceDataReader implements SourceDataReader {

	private $data;
	private $count;

	public function __construct( array $sourcefiles ) {
		$this->data = [];
		foreach ( $sourcefiles as $idx => $file ) {
			$this->data[] = new SourceData( $idx + 1, '', file_get_contents( __DIR__ . '/../data/' . $file . '.txt' ) );
		}
		$this->count = 0;
	}

	public function hasMoreRows(): bool {
		return $this->count < count( $this->data );
	}

	public function getRow(): SourceData {
		return $this->data[$this->count++];
	}

}