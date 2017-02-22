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

	private const READ_BYTES_SIZE = 8192;

	public function testAddressesWithSucceedingValidatorWritesCSVToOutput() {
		$reader = new FileSourceDataReader( [ 'one_line_address', 'multiline_address' ] );
		$extractor = new ExtractAddressUseCase( new SucceedingSourceDataValidator() );
		$outputStream = new \SplFileObject( 'php://memory', 'r+' );
		$rejectedStream = new \SplFileObject( 'php://memory', 'r+' );
		$extractor->extractAddresses(
			$reader,
			new FoundAddressWriter( $outputStream, 'http://example.com/?ticket=%d' ),
			new RejectedAddressWriter( $rejectedStream )
		);
		$outputStream->rewind();
		$rejectedStream->rewind();

		$this->assertSame(
			$this->loadFile( 'output.csv' ),
			$outputStream->fread( self::READ_BYTES_SIZE )
		);
		$this->assertSame( '', $rejectedStream->fread( self::READ_BYTES_SIZE ) );
	}

	public function testAddressesWithFailingValidatorWritesCSVToReject() {
		$reader = new FileSourceDataReader( [ 'one_line_address', 'multiline_address' ] );
		$extractor = new ExtractAddressUseCase( new FailingSourceDataValidator() );
		$outputStream = new \SplFileObject( 'php://memory', 'r+' );
		$rejectedStream = new \SplFileObject( 'php://memory', 'r+' );
		$extractor->extractAddresses(
			$reader,
			new FoundAddressWriter( $outputStream, '%s' ),
			new RejectedAddressWriter( $rejectedStream )
		);
		$outputStream->rewind();
		$rejectedStream->rewind();

		$this->assertSame( '', $outputStream->fread( self::READ_BYTES_SIZE ) );
		$this->assertSame( $this->loadFile( 'rejected.csv' ), $rejectedStream->fread( self::READ_BYTES_SIZE ) );
	}

	private function loadFile( string $fixtureName ): string {
		return file_get_contents( __DIR__ . '/../data/' . $fixtureName );
	}

}
