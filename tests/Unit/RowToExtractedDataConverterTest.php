<?php
declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress\Test\Unit;

use WMDE\OtrsExtractAddress\DataAccess\RowToExtractedDataConverter;
use WMDE\OtrsExtractAddress\Domain\Address;
use WMDE\OtrsExtractAddress\Domain\UniqueId;
use WMDE\OtrsExtractAddress\UseCases\ExtractAddress\ExtractedData;

class RowToExtractedDataConverterTest extends \PHPUnit_Framework_TestCase {

	public function testItConvertsOneRowIntoExtractedData() {
		$sourceData = new \ArrayIterator( [
			[
				1,
				'hank.scorpio@globex.com',
				'Re: Spendenquittung 2016 - bitte bestätigen Sie Ihre Adresse',
				'Adressnummer',
				'100200',
				'Washingtonplatz',
				'12345',
				'Berlin'
			]
		] );
		$converter = new RowToExtractedDataConverter( $sourceData );
		$converter->rewind();
		$this->assertEquals( new ExtractedData(
			1,
			'hank.scorpio@globex.com',
			'Re: Spendenquittung 2016 - bitte bestätigen Sie Ihre Adresse',
			new Address(
				'Washingtonplatz',
				'12345',
				'Berlin'
			),
			UniqueId::newAddressNumber( 100200 )
		), $converter->current() );
	}

	public function testItConvertsTwoRowsIntoExtractedData() {
		$sourceData = new \ArrayIterator( [
			[
				1,
				'hank.scorpio@globex.com',
				'Re: Spendenquittung 2016 - bitte bestätigen Sie Ihre Adresse',
				'Adressnummer',
				'100200',
				'Washingtonplatz',
				'12345',
				'Berlin'
			],
			[
				5,
				'homer.simpson@globex.com',
				'Re: Spendenquittung 2016 - bitte bestätigen Sie Ihre Adresse',
				'Mitgliedsnummer',
				'3344',
				'Europaplatz',
				'12345',
				'Berlin'
			],
		] );
		$converter = new RowToExtractedDataConverter( $sourceData );
		$converter->rewind();
		$firstItem = $converter->current();
		$converter->next();
		$secondItem = $converter->current();

		$this->assertEquals( new ExtractedData(
			1,
			'hank.scorpio@globex.com',
			'Re: Spendenquittung 2016 - bitte bestätigen Sie Ihre Adresse',
			new Address(
				'Washingtonplatz',
				'12345',
				'Berlin'
			),
			UniqueId::newAddressNumber( 100200 )
		), $firstItem );
		$this->assertEquals( new ExtractedData(
			5,
			'homer.simpson@globex.com',
			'Re: Spendenquittung 2016 - bitte bestätigen Sie Ihre Adresse',
			new Address(
				'Europaplatz',
				'12345',
				'Berlin'
			),
			UniqueId::newMembershipNumber( 3344 )
		), $secondItem );

	}

}
