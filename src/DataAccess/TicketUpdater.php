<?php

declare(strict_types = 1);

namespace WMDE\OtrsExtractAddress\DataAccess;

use Doctrine\DBAL\Connection;

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

	public function __construct( Connection $db, int $userId ) {
		$this->db = $db;
		$this->userId = $userId;
	}

	public function updateTicket( int $ticketNumber, \DateTime $updateTime ) {
		$ticketId = $this->getTicketId( $ticketNumber );
		$this->changeTicketOwner( $ticketId, $updateTime );
		$this->addHistoryItemForOwnerChange( $ticketId, $updateTime );
		// TODO:
		// Create article with internal note
		// add history item for article
	}

	private function changeTicketOwner( int $ticketId, \DateTime $updateTime ) {
		$this->db->prepare( 'UPDATE ticket SET user_id=:user_id, change_by=:user_id, change_time=:update_time WHERE id=:ticket_id' )
			->execute([
				'ticket_id' => $ticketId,
				'user_id' => $this->userId,
				'update_time' => $updateTime->format( 'Y-m-d H:i:s')
			] );
	}

	private function getTicketId( int $ticketNumber ): int {
		$stmt = $this->db->prepare( 'SELECT id FROM ticket WHERE tn=:ticket_number' );
		$stmt->execute( [ 'ticket_number' => $ticketNumber] );
		return (int) $stmt->fetchColumn();
	}

	private function addHistoryItemForOwnerChange( int $ticketId, \DateTime $updateTime ) {
		$values = [
			'name' => ':name',
			'history_type_id' => 23,
			'ticket_id' => ':ticket_id',
			'type_id' => 1,
			'queue_id' => 5,
			'owner_id' => ':user_id',
			'priority_id' => 3,
			'state_id' => 1,
			'create_time' => ':update_time',
			'create_by' => ':user_id',
			'change_time' => ':update_time',
			'change_by' => ':user_id',
		];
		$stmt = $this->db->prepare(
			'INSERT INTO ticket_history( ' . implode( ', ', array_keys( $values ) ) . ')'.
			' VALUES (' . implode( ',', $values ) . ')'
		);
		$stmt->execute( [
			'ticket_id' => $ticketId,
			'name' => '%%'.$this->getUsername( $this->userId ) . '%%' . $this->userId,
			'user_id' => $this->userId,
			'update_time' => $updateTime->format( 'Y-m-d H:i:s')
		] );
	}

	private function getUsername( int $userId ): string {
		$stmt = $this->db->prepare( 'SELECT login FROM users WHERE id=:user_id' );
		$stmt->execute( [ 'user_id' => $userId] );
		return $stmt->fetchColumn();
	}
}