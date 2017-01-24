<?php

declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress\Test\Unit;

use WMDE\OtrsExtractAddress\Address;
use WMDE\OtrsExtractAddress\AddressFilter;
use WMDE\OtrsExtractAddress\AddressFinder;

class AddressFinderTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var AddressFinder
	 */
	private $addressFinder;

	public function setUp() {
		$this->addressFinder = new AddressFinder( new AddressFilter() );
	}

	public function testGivenAnEmptyStringItReturnsNull() {
		$this->assertSame( [], $this->addressFinder->findAddresses( '' ) );
	}

	public function testGivenAnAddressOnOneLineItIsExtracted() {
		$this->assertEquals(
			[ new Address( 'Irrweg 7', '12345', 'Berlin' ) ],
			$this->addressFinder->findAddresses( $this->loadFile( 'one_line_address' ) )
		);
	}

	public function testGivenAnAddressOnFirstLineItIsExtracted() {
		$this->assertEquals(
			[ new Address( 'Irrweg 7', '12345', 'Berlin' ) ],
			$this->addressFinder->findAddresses( $this->loadFile( 'first_line_address' ) )
		);
	}

	public function testGivenAnAddressOnLastLineItIsExtracted() {
		$this->assertEquals(
			[ new Address( 'Irrweg 7', '12345', 'Berlin' ) ],
			$this->addressFinder->findAddresses( $this->loadFile( 'last_line_address' ) )
		);
	}

	public function testGivenAnAddressSeveralLinesItIsExtracted() {
		$this->assertEquals(
			[ new Address( 'Irrweg 7', '12345', 'Berlin' ) ],
			$this->addressFinder->findAddresses( $this->loadFile( 'multiline_address' ) )
		);
	}

	public function testGivenMultipleAddresssesAllAreExtracted() {
		$this->assertEquals(
			[
				new Address( 'Irrweg 7', '12345', 'Berlin' ),
				new Address( 'Im Graben 6', '10203', 'Berlin' ),
			],
			$this->addressFinder->findAddresses( $this->loadFile( 'multiple_addresses' ) )
		);
	}

	public function testGivenMultipleAddresssesValidAndInvalidAddressesAreExtracted() {
		$this->assertEquals(
			[
				new Address( '', '30000', 'Hannover' ),
				new Address( 'Irrweg 7', '12345', 'Berlin' ),
			],
			$this->addressFinder->findAddresses( $this->loadFile( 'multiple_addresses_one_invalid' ) )
		);
	}

	public function testGivenMultipleInvalidAddresssesAreExtracted() {
		$this->assertEquals(
			[
				new Address( '', '30000', 'Hannover' ),
				new Address( 'Die Straße ist gleich geblieben, nur meine Postleitzahl ist jetzt', '12345', '' )
			],
			$this->addressFinder->findAddresses( $this->loadFile( 'multiple_addresses_all_invalid' ) )
		);
	}

	private function loadFile( string $fixtureName ): string {
		return file_get_contents( __DIR__ . '/../data/' . $fixtureName . '.txt' );
	}
}
