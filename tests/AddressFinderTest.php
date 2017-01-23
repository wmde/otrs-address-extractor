<?php

declare(strict_types = 1);

namespace WMDE\OtrsExtractAddress\Test;

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
		$this->assertNull( $this->addressFinder->findAddress( '' ) );
	}

	public function testGivenAnAddressOnOneLineItIsExtracted() {
		$this->assertEquals(
			new Address( 'Irrweg 7', '12345', 'Berlin'),
			$this->addressFinder->findAddress( $this->loadFixture( 'one_line_address' ) )
		);
	}

	public function testGivenAnAddressOnFirstLineItIsExtracted() {
		$this->assertEquals(
			new Address( 'Irrweg 7', '12345', 'Berlin'),
			$this->addressFinder->findAddress( $this->loadFixture( 'first_line_address' ) )
		);
	}

	public function testGivenAnAddressSeveralLinesItIsExtracted() {
		$this->assertEquals(
			new Address( 'Irrweg 7', '12345', 'Berlin'),
			$this->addressFinder->findAddress( $this->loadFixture( 'multiline_address' ) )
		);
	}

	public function testGivenMultipleAddresssesOnlyTheFirstIsExtracted() {
		$this->assertEquals(
			new Address( 'Irrweg 7', '12345', 'Berlin'),
			$this->addressFinder->findAddress( $this->loadFixture( 'multiple_addresses' ) )
		);
	}

	public function testGivenMultipleAddresssesOnlyTheFirstValidAddressIsExtracted() {
		$this->assertEquals(
			new Address( 'Irrweg 7', '12345', 'Berlin'),
			$this->addressFinder->findAddress( $this->loadFixture( 'multiple_addresses_one_invalid' ) )
		);
	}

	public function testGivenMultipleInvalidAddresssesNullIsReturned() {
		$this->assertNull(
			$this->addressFinder->findAddress( $this->loadFixture( 'multiple_addresses_all_invalid' ) )
		);
	}

	public function testGivenAnExcludedAddressItIsIgnored() {
		$finder = new AddressFinder( new AddressFilter([
			new Address( 'Irrweg 7', '12345', 'Berlin')
		] ) );
		$this->assertEquals(
			new Address( 'Im Graben 6', '10203', 'Berlin'),
			$finder->findAddress( $this->loadFixture( 'multiple_addresses' ) )
		);
	}

	private function loadFixture( string $fixtureName ): string {
		return file_get_contents( __DIR__ . '/data/' . $fixtureName . '.txt' );
	}
}
