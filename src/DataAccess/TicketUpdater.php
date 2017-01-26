<?php

declare(strict_types = 1);

namespace WMDE\OtrsExtractAddress\DataAccess;

/**
 * @license GNU GPL v2+
 * @author Gabriel Birke < gabriel.birke@wikimedia.de >
 */
class TicketUpdater {

	/**
	 * @var \PDO
	 */
	private $db;
	private $userId;

	public function __construct( \PDO $db, int $userId ) {
		$this->db = $db;
		$this->userId = $userId;
	}

	public function updateTicket( int $ticketNumber, \DateTime $updateTime ) {
		$this->changeTicketOwner( $ticketNumber, $updateTime );
		// TODO:
		// add history item for owner change
		// Create article with internal note
		// add history item for article
	}

	private function changeTicketOwner( int $ticketNumber, \DateTime $updateTime ) {
		$this->db->prepare( 'UPDATE ticket SET user_id=:user_id, change_by=:user_id, change_time=:update_time WHERE tn=:ticket_number' )
			->execute([
				'ticket_number' => $ticketNumber,
				'user_id' => $this->userId,
				'update_time' => $updateTime->format( 'Y-m-d H:i:s')
			] );
	}

}