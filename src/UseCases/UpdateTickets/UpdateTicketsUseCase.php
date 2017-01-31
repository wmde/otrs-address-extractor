<?php

declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress\UseCases\UpdateTickets;

use WMDE\OtrsExtractAddress\OtrsConnector;

/**
 * @license GNU GPL v2+
 * @author Gabriel Birke < gabriel.birke@wikimedia.de >
 */
class UpdateTicketsUseCase {

	private $otrsConnector;

	public function __construct( OtrsConnector $otrsConnector ) {
		$this->otrsConnector = $otrsConnector;
	}

	public function updateTickets( \Iterator $extractedData, int $newOwnerId ) {
		foreach ( $extractedData as $item ) {
			$this->otrsConnector->setTicketOwner( (string) $item->getTicketNumber(), $newOwnerId );
		}
	}
}