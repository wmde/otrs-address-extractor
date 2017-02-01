<?php
declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress\Test\Unit;

use Psr\Log\LogLevel;
use WMDE\OtrsExtractAddress\OtrsConnector;
use WMDE\OtrsExtractAddress\OtrsConnectorException;
use WMDE\OtrsExtractAddress\Test\Fixtures\LoggerSpy;
use WMDE\OtrsExtractAddress\UseCases\UpdateTickets\UpdateTicketsUseCase;

class UpdateTicketsUseCaseTest extends \PHPUnit_Framework_TestCase {

	const NEW_OWNER_ID = 99;

	public function testTicketsAreUpdatedWithNewOwner() {
		$otrsConnector = $this->getMockBuilder( OtrsConnector::class )->disableOriginalConstructor()->getMock();
		$otrsConnector->expects( $this->at( 0 ) )
			->method( 'setTicketOwner' )
			->with( '1', self::NEW_OWNER_ID );
		$otrsConnector->expects( $this->at( 1 ) )
			->method( 'setTicketOwner' )
			->with( '5', self::NEW_OWNER_ID );
		$useCase = new UpdateTicketsUseCase( $otrsConnector );

		$useCase->updateTickets( new \ArrayIterator( [ '1', '5' ] ), self::NEW_OWNER_ID );
	}

	public function testNumberOfUpdateTicketsIsReturned() {
		$otrsConnector = $this->getMockBuilder( OtrsConnector::class )->disableOriginalConstructor()->getMock();
		$useCase = new UpdateTicketsUseCase( $otrsConnector );
		$this->assertSame( 3, $useCase->updateTickets( new \ArrayIterator( [ '1', '5', '2347' ] ), self::NEW_OWNER_ID ) );
	}

	public function testOtrsExceptionsAreLogged() {
		$otrsConnector = $this->getMockBuilder( OtrsConnector::class )->disableOriginalConstructor()->getMock();
		$otrsConnector->expects( $this->at( 1 ) )
			->method( 'setTicketOwner' )
			->will( $this->throwException( new OtrsConnectorException( 'Oh no' ) ) );
		$logger = new LoggerSpy();
		$useCase = new UpdateTicketsUseCase( $otrsConnector, $logger );
		$this->assertSame( 2, $useCase->updateTickets( new \ArrayIterator( [ '1', '5', '2347' ] ), self::NEW_OWNER_ID ) );
		$this->assertEquals(
			[
				0 => [ LogLevel::ERROR, 'Oh no', [] ]
			],
			$logger->getLogs()
		);
	}
}
