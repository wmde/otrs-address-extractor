<?php
declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress\Test\Unit;

use WMDE\OtrsExtractAddress\OtrsConnector;
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

		$useCase->updateTickets( new \ArrayIterator( [ 1, 5 ] ), self::NEW_OWNER_ID );
	}
}
