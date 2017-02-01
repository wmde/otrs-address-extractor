<?php

declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress\UseCases\UpdateTickets;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use WMDE\OtrsExtractAddress\OtrsConnector;
use WMDE\OtrsExtractAddress\OtrsConnectorException;

/**
 * @license GNU GPL v2+
 * @author Gabriel Birke < gabriel.birke@wikimedia.de >
 */
class UpdateTicketsUseCase {

	private $otrsConnector;
	private $logger;

	public function __construct( OtrsConnector $otrsConnector, LoggerInterface $logger=null ) {
		$this->otrsConnector = $otrsConnector;
		if (is_null( $logger ) ) {
			$logger = new NullLogger();
		}
		$this->logger = $logger;
	}

	public function updateTickets( \Traversable $ticketNumbers, int $newOwnerId ): int {
		$counter = 0;
		foreach ( $ticketNumbers as $ticketNumber ) {
			try {
				$this->otrsConnector->setTicketOwner( $ticketNumber, $newOwnerId );
				$counter++;
			} catch ( OtrsConnectorException $e ) {
				$this->logger->error( $e->getMessage() );
			}

		}
		return $counter;
	}

	public function setLogger( $logger ) {
		$this->logger = $logger;
	}
}