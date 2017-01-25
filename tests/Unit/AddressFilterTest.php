<?php

declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress\Test\Unit;

use WMDE\OtrsExtractAddress\Domain\Address;
use WMDE\OtrsExtractAddress\AddressFilter;

class AddressFilterTest extends \PHPUnit_Framework_TestCase {

	public function testGivenInvalidAddressesTheyAreFiltered() {
		$filter = new AddressFilter();
		$this->assertEquals(
			new Address( 'Im Graben 6', '10203', 'Berlin' ),
			$filter->firstValidAddress( [
				new Address( '', '12345', 'Berlin' ),
				new Address( 'Im Graben 6', '10203', 'Berlin' ),
				new Address( 'Heimweg 23', '', 'Berlin' )
			] )
		);
	}

	public function testGivenAnExcludedAddressItIsIgnored() {
		$filter = new AddressFilter( [
			new Address( 'Irrweg 7', '12345', 'Berlin' )
		] );
		$this->assertEquals(
			new Address( 'Im Graben 6', '10203', 'Berlin' ),
			$filter->firstValidAddress( [
				new Address( 'Irrweg 7', '12345', 'Berlin' ),
				new Address( 'Im Graben 6', '10203', 'Berlin' ),
				new Address( 'Heimweg 23', '10405', 'Berlin' )
			] )
		);
	}

	public function testGetValidAddressesReturnsAllValidAddresses() {
		$filter = new AddressFilter();
		$this->assertEquals(
			[
				new Address( 'Im Graben 6', '10203', 'Berlin' ),
				new Address( 'Heimweg 23', '10405', 'Berlin' )
			],
			$filter->getValidAddresses( [
				new Address( '', '12345', 'Berlin' ),
				new Address( 'Im Graben 6', '10203', 'Berlin' ),
				new Address( 'Heimweg 23', '10405', 'Berlin' )
			] )
		);
	}

	public function testGivenMultipleIdenticalAddressesOnlyOneIsReturned() {
		$filter = new AddressFilter();
		$this->assertEquals(
			[ new Address( 'Im Graben 6', '10203', 'Berlin' ) ],
			$filter->getValidAddresses( [
				new Address( 'Im Graben 6', '10203', 'Berlin' ),
				new Address( 'Im Graben 6', '10203', 'Berlin' ),
				new Address( 'Im Graben 6', '10203', 'Berlin' ),
			] )
		);
	}
}
