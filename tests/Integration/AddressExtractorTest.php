<?php

declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress\Test\Integration;

use WMDE\OtrsExtractAddress\UseCases\ExtractAddress\ExtractAddressUseCase;
use WMDE\OtrsExtractAddress\Test\Fixtures\FailingSourceDataValidator;
use WMDE\OtrsExtractAddress\Test\Fixtures\FileSourceDataReader;
use WMDE\OtrsExtractAddress\Test\Fixtures\SucceedingSourceDataValidator;
use WMDE\OtrsExtractAddress\UseCases\ExtractAddress\FoundAddressWriter;
use WMDE\OtrsExtractAddress\UseCases\ExtractAddress\RejectedAddressWriter;

class AddressExtractorTest extends \PHPUnit_Framework_TestCase {

	public function testAddressesWithSucceedingValidatorWritesCSVToOutput() {
		$reader = new FileSourceDataReader( [ 'one_line_address', 'multiline_address' ] );
		$extractor = new ExtractAddressUseCase( new SucceedingSourceDataValidator() );
		$outputStream = fopen( 'php://memory', 'r+' );
		$rejectedStream = fopen( 'php://memory', 'r+' );
		$extractor->extractAddresses(
			$reader,
			new FoundAddressWriter( $outputStream, 'http://example.com/?ticket=%d' ),
			new RejectedAddressWriter( $rejectedStream )
		);
		rewind( $outputStream );
		rewind( $rejectedStream );

		$this->assertSame(
			$this->loadFile( 'output.csv' ),
			stream_get_contents( $outputStream )
		);
		$this->assertSame( '', stream_get_contents( $rejectedStream ) );
	}

	public function testAddressesWithFailingValidatorWritesCSVToReject() {
		$reader = new FileSourceDataReader( [ 'one_line_address', 'multiline_address' ] );
		$extractor = new ExtractAddressUseCase( new FailingSourceDataValidator() );
		$outputStream = fopen( 'php://memory', 'r+' );
		$rejectedStream = fopen( 'php://memory', 'r+' );
		$extractor->extractAddresses(
			$reader,
			new FoundAddressWriter( $outputStream, '%s' ),
			new RejectedAddressWriter( $rejectedStream )
		);
		rewind( $outputStream );
		rewind( $rejectedStream );

		$this->assertSame( '', stream_get_contents( $outputStream ) );
		$this->assertSame( $this->loadFile( 'rejected.csv' ), stream_get_contents( $rejectedStream ) );
	}

	private function loadFile( string $fixtureName ): string {
		return file_get_contents( __DIR__ . '/../data/' . $fixtureName );
	}

}
