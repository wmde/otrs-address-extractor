<?php

declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress\Test;

use WMDE\OtrsExtractAddress\Address;


class AddressTest extends \PHPUnit_Framework_TestCase {

	public function testGivenAnAddressWithAllFieldsIsValidReturnsTrue() {
		$address = new Address( 'Auf dem Weg', '30000', 'Hannover' );
		$this->assertTrue( $address->isValid() );
	}

	/**
	 * @dataProvider emptyFieldProvider
	 */
	public function testGivenAddressWithEmptyFieldsIsValidReturnsFalse( string $street, string $postcode, string $address ) {
		$address = new Address( $street, $postcode, $address );
		$this->assertFalse( $address->isValid() );
	}

	public function emptyFieldProvider(): array {
		return [
			[ '', '30000', 'Hannover' ],
			[ 'Auf dem Weg', '', 'Hannover' ],
			[ 'Auf dem Weg', '30000', '' ],
		];
	}
}
