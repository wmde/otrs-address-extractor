<?php

declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress\Test\Integration;

use WMDE\OtrsExtractAddress\UseCases\ExtractAddress\ExtractAddressUseCase;
use WMDE\OtrsExtractAddress\Test\Fixtures\FailingSourceDataValidator;
use WMDE\OtrsExtractAddress\Test\Fixtures\FileSourceDataReader;
use WMDE\OtrsExtractAddress\Test\Fixtures\SucceedingSourceDataValidator;

class AddressExtractorTest extends \PHPUnit_Framework_TestCase {

	public function testAddressesWithSucceedingValidatorWritesCSVToOutput() {
		$reader = new FileSourceDataReader( [ 'one_line_address', 'multiline_address' ] );
		$extractor = new ExtractAddressUseCase( new SucceedingSourceDataValidator() );
		$output = fopen( 'php://memory', 'r+' );
		$rejected = fopen( 'php://memory', 'r+' );
		$extractor->extractAddresses( $reader, $output, $rejected );
		rewind( $output );
		rewind( $rejected );

		$this->assertSame(
			$this->loadFile( 'output.csv' ),
			stream_get_contents( $output )
		);
		$this->assertSame( '', stream_get_contents( $rejected ) );
	}

	public function testAddressesWithFailingValidatorWritesCSVToReject() {
		$reader = new FileSourceDataReader( [ 'one_line_address', 'multiline_address' ] );
		$extractor = new ExtractAddressUseCase( new FailingSourceDataValidator() );
		$output = fopen( 'php://memory', 'r+' );
		$rejected = fopen( 'php://memory', 'r+' );
		$extractor->extractAddresses( $reader, $output, $rejected );
		rewind( $output );
		rewind( $rejected );

		$this->assertSame( '', stream_get_contents( $output ) );
		$this->assertSame( $this->loadFile( 'rejected.csv' ), stream_get_contents( $rejected ) );
	}

	private function loadFile( string $fixtureName ): string {
		return file_get_contents( __DIR__ . '/../data/' . $fixtureName );
	}

}
