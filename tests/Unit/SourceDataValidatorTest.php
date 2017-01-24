<?php

namespace WMDE\OtrsExtractAddress\Test\Unit;

use WMDE\OtrsExtractAddress\Address;
use WMDE\OtrsExtractAddress\AddressFilter;
use WMDE\OtrsExtractAddress\SourceData;
use WMDE\OtrsExtractAddress\SourceDataValidationResult;
use WMDE\OtrsExtractAddress\SourceDataValidator;
use WMDE\OtrsExtractAddress\UniqueId;

class SourceDataValidatorTest extends \PHPUnit_Framework_TestCase
{
	public function testGivenNoAddressesValidationFails() {
		$sourceData = $this->createMock( SourceData::class );
		$sourceData->method( 'getAddresses' )->willReturn( [] );
		$validator = new SourceDataValidator( $this->createAddressFilter() );

		$result = $validator->validate( $sourceData );

		$this->assertFalse( $result->isValid() );
		$this->assertSame( SourceDataValidationResult::ERR_NO_ADDRESS, $result->getValidationError() );
	}

	public function testSeveralAddressesValidationFails() {
		$sourceData = $this->createMock( SourceData::class );
		$sourceData->method( 'getAddresses' )->willReturn( [
			new Address( 'Im Graben 6', '10203', 'Berlin' ),
			new Address( 'Heimweg 23', '10405', 'Berlin' )
		] );
		$validator = new SourceDataValidator( $this->createAddressFilter() );

		$result = $validator->validate( $sourceData );

		$this->assertFalse( $result->isValid() );
		$this->assertSame( SourceDataValidationResult::ERR_TOO_MANY_ADDRESSES, $result->getValidationError() );
	}

	public function testNoUniqueIdValidationFails() {
		$sourceData = $this->createMock( SourceData::class );
		$sourceData->method( 'getAddresses' )->willReturn( [
			new Address( 'Im Graben 6', '10203', 'Berlin' ),
		] );
		$sourceData->method( 'getUniqueIds' )->willReturn( [] );
		$validator = new SourceDataValidator( $this->createAddressFilter() );

		$result = $validator->validate( $sourceData );

		$this->assertFalse( $result->isValid() );
		$this->assertSame( SourceDataValidationResult::ERR_NO_UNIQUE_ID, $result->getValidationError() );
	}

	public function testUniqueIdEqualsPostcodeValidationFails() {
		$sourceData = $this->createMock( SourceData::class );
		$sourceData->method( 'getAddresses' )->willReturn( [
			new Address( 'Im Graben 6', '10203', 'Berlin' ),
		] );
		$sourceData->method( 'getUniqueIds' )->willReturn( [
			UniqueId::newMembershipNumber( 10203 )
		] );
		$validator = new SourceDataValidator( $this->createAddressFilter() );

		$result = $validator->validate( $sourceData );

		$this->assertFalse( $result->isValid() );
		$this->assertSame( SourceDataValidationResult::ERR_NO_UNIQUE_ID, $result->getValidationError() );
	}

	public function testGivenValidDataValidationSucceeds() {
		$address = new Address( 'Im Graben 6', '10203', 'Berlin' );
		$sourceData = $this->createMock( SourceData::class );
		$sourceData->method( 'getAddresses' )->willReturn( [ $address ] );
		$sourceData->method( 'getUniqueIds' )->willReturn( [
			UniqueId::newMembershipNumber( 123 )
		] );
		$validator = new SourceDataValidator( $this->createAddressFilter() );

		$result = $validator->validate( $sourceData );

		$this->assertTrue( $result->isValid() );
		$this->assertSame( $address, $result->getAddress() );
	}

	private function createAddressFilter(): AddressFilter {
		$filter = $this->getMockBuilder( AddressFilter::class )->setMethods( [ 'getValidAddresses' ] )->getMock();
		$filter->method( 'getValidAddresses' )
			->will( $this->returnArgument( 0 ) );
		return $filter;
	}
}
