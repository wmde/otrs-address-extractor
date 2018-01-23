<?php

declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress\Test\Unit;

use WMDE\OtrsExtractAddress\DataAccess\TicketNumberReader;

class TicketNumberReaderTest extends \PHPUnit_Framework_TestCase {

	public function testItReadsNumbersFromCSVFormattedInput() {
		$reader = new TicketNumberReader( __DIR__ . '/../data/ticket_numbers.csv' );
		$this->assertSame( [ '1', '2', '3' ], iterator_to_array( $reader ) );
	}

}
