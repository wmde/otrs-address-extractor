<?php
declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress\Test\Unit;

use WMDE\OtrsExtractAddress\Domain\Address;
use WMDE\OtrsExtractAddress\Domain\UniqueId;
use WMDE\OtrsExtractAddress\OtrsConnector;
use WMDE\OtrsExtractAddress\UseCases\ExtractAddress\ExtractedData;
use WMDE\OtrsExtractAddress\UseCases\UpdateTickets\UpdateTicketsUseCase;

class UpdateTicketsUseCaseTest extends \PHPUnit_Framework_TestCase {

	const NEW_OWNER_ID = 99;

	public function testTicketsAreUpdatedWithNewOwner() {
		$otrsConnector = $this->getMockBuilder( OtrsConnector::class )->disableOriginalConstructor()->getMock();
		$otrsConnector->expects( $this->at( 0 ) )
			->method( 'setTicketOwner' )
			->with( 1, self::NEW_OWNER_ID );
		$otrsConnector->expects( $this->at( 1 ) )
			->method( 'setTicketOwner' )
			->with( 5, self::NEW_OWNER_ID );
		$useCase = new UpdateTicketsUseCase( $otrsConnector );

		$data = new \ArrayIterator( [
			new ExtractedData(
				1,
				'hank.scorpio@globex.com',
				'Re: Spendenquittung 2016 - bitte bestätigen Sie Ihre Adresse',
				$this->newEmptyAddress(),
				$this->newFakeUniqueId()
			),
			new ExtractedData(
				5,
				'homer.simpson@globex.com',
				'Re: Spendenquittung 2016 - bitte bestätigen Sie Ihre Adresse',
				$this->newEmptyAddress(),
				$this->newFakeUniqueId()
			)
		] );

		$useCase->updateTickets( $data, self::NEW_OWNER_ID );
	}

	private function newEmptyAddress(): Address {
		return new Address( '', '', ' ' );
	}

	private function newFakeUniqueId() {
		return UniqueId::newAddressNumber( 0 );
	}

}
