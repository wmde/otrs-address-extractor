<?php

declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress\Test;

use WMDE\OtrsExtractAddress\UniqueId;
use WMDE\OtrsExtractAddress\UniqueIdFinder;

class UniqueIdFinderTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var UniqueIdFinder
	 */
	private $finder;

	protected function setUp() {
		$this->finder = new UniqueIdFinder();
	}

	public function testGivenEmptyStringIdFinderFindsNoNumbers() {
		$this->assertCount( 0, $this->finder->findIds( '' ) );
	}

	public function testMissingNumberDefinitionFindsNoNumbers() {
		$this->assertCount( 0, $this->finder->findIds( 'Tel. 030/123 445 ' ) );
	}

	public function testFollowingMembershipNumber() {
		$ids = $this->finder->findIds( 'Mitgliedsnummer 123' );
		$this->assertCount( 1, $ids );
		$this->assertEquals(
			UniqueId::newMembershipNumber( 123 ),
			$ids[0]
		);
	}

	public function testPrecedingMembershipNumber() {
		$ids = $this->finder->findIds( '123 ist meine Mitgliedsnr.' );
		$this->assertCount( 1, $ids );
		$this->assertEquals(
			UniqueId::newMembershipNumber( 123 ),
			$ids[0]
		);
	}

	public function testFollowingAddressNumber() {
		$ids = $this->finder->findIds('Adressnr. 6060842' );
		$this->assertCount( 1, $ids );
		$this->assertEquals(
			UniqueId::newAddressNumber( 6060842 ),
			$ids[0]
		);
	}

	public function testPrecedingAddressNumber() {
		$ids = $this->finder->findIds('6060842 ist meine Adressnummer' );
		$this->assertCount( 1, $ids );
		$this->assertEquals(
			UniqueId::newAddressNumber( 6060842 ),
			$ids[0]
		);
	}

	public function testFindingMultipleNumbers() {
		$this->assertEquals(
			[
				UniqueId::newAddressNumber( 12345 ),
				UniqueId::newAddressNumber( 6060842 )
			],
			$this->finder->findIds('12345 Berlin, Adressnummer 6060842' )
		);
	}

}
