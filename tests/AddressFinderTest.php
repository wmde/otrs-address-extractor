<?php

declare(strict_types = 1);

namespace WMDE\OtrsExtractAddress\Test;

use WMDE\OtrsExtractAddress\Address;
use WMDE\OtrsExtractAddress\AddressFinder;

class AddressFinderTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var AddressFinder
	 */
	private $addressFinder;

	public function setUp() {
		$this->addressFinder = new AddressFinder();
	}

	public function testGivenAnEmptyStringItReturnsNull() {
		$this->assertNull( $this->addressFinder->findAddress( '' ) );
	}

	public function testGivenAnAddressOnOneLineItIsExtracted() {
		$this->assertEquals(
			new Address( 'Irrweg 7', '12345', 'Berlin'),
			$this->addressFinder->findAddress( $this->loadFixture( 'one_line_address' ) )
		);
	}

	private function loadFixture( string $fixtureName ): string {
		return file_get_contents( __DIR__ . '/data/' . $fixtureName . '.txt' );
	}
}
