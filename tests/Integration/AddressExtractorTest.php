<?php

declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress\Test\Integration;

use WMDE\OtrsExtractAddress\AddressExtractor;
use WMDE\OtrsExtractAddress\Test\Fixtures\FileSourceDataReader;
use WMDE\OtrsExtractAddress\Test\Fixtures\SucceedingSourceDataValidator;

class AddressExtractorTest extends \PHPUnit_Framework_TestCase {

	public function testAddressesWithSucceedingValidator() {
		$reader = new FileSourceDataReader( [ 'one_line_address', 'multiline_address' ] );
		$extractor = new AddressExtractor( new SucceedingSourceDataValidator() );
		$output = fopen( 'php://memory', 'r+' );
		$rejected = fopen( 'php://memory', 'r+' );
		$extractor->extractAddresses( $reader, $output, $rejected );
		rewind( $output );
		rewind( $rejected );

		$this->assertSame( '', stream_get_contents( $rejected ) );
		$this->assertSame(
			'"1";;"Mitgliedsnummer";"66778899";"Irrweg 7";"12345";"Berlin"' .
			"\n" .
			'"2";;"Adressnummer";"90807060";"Irrweg 7";"12345";"Berlin"' .
			"\n" ,
			stream_get_contents( $output )
		);
	}

	// TODO testAddressesWithFailingValidator

}
