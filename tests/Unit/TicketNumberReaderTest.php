<?php

declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress\Test\Unit;

use WMDE\OtrsExtractAddress\DataAccess\TicketNumberReader;

class TicketNumberReaderTest extends \PHPUnit_Framework_TestCase {

	public function testItReadsNumbersFromCSVFormattedInput() {
		$input = new \SplFileObject( 'php://memory', 'r+' );
		$input->fputcsv( [ 1, null, null, null ], ';', '"' );
		$input->fputcsv( [ 2, null, null, null ], ';', '"' );
		$input->fputcsv( [ 3, null, null, null ], ';', '"' );
		$input->rewind();

		$reader = new TicketNumberReader( $input );
		$this->assertSame( [ '1', '2', '3' ], iterator_to_array( $reader ) );
	}

}
