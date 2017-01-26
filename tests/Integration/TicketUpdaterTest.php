<?php

declare(strict_types = 1);

namespace WMDE\OtrsExtractAddress\Test\Integration;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use WMDE\OtrsExtractAddress\DataAccess\TicketUpdater;

/**
 * @license GNU GPL v2+
 * @author Gabriel Birke < gabriel.birke@wikimedia.de >
 */
class TicketUpdaterTest extends \PHPUnit_Framework_TestCase {

	const CHANGED_USER_ID = 99;

	/**
	 * @var Connection
	 */
	private $db = null;

	protected function setUp()
	{
		parent::setUp();

		if ( $this->db == null ) {
			$this->db = DriverManager::getConnection( [ 'url' => 'sqlite:///:memory:' ] );
			$this->createDbSchema();
			$this->insertInitialData();
		}
		$this->db->beginTransaction();
	}

	protected function tearDown() {
		parent::tearDown();
		$this->db->rollBack();
	}

	public function testUpdateTickedChangesOwnerAndAccessTime() {
		$ticket = $this->db->query( 'SELECT * FROM ticket WHERE id=1' )->fetch( \PDO::FETCH_ASSOC );
		$ticketUpdater = new TicketUpdater( $this->db, self::CHANGED_USER_ID );

		$ticketUpdater->updateTicket( 2017011975011968, new \DateTime( '2017-01-20 14:00:00' ) );
		$changedTicket = $this->db->query( 'SELECT * FROM ticket WHERE id=1' )->fetch( \PDO::FETCH_ASSOC );
		$this->assertEquals( [
			'user_id' => 99,
			'change_time' => '2017-01-20 14:00:00',
			'change_by' => 99
		], array_diff_assoc( $changedTicket, $ticket ) );
	}

	public function testUpdateTickedAddsOwnerChangeToHistory() {
		$ticketUpdater = new TicketUpdater( $this->db, self::CHANGED_USER_ID );
		$ticketUpdater->updateTicket( 2017011975011968, new \DateTime( '2017-01-20 14:00:00' ) );
		$history = $this->db->query( 'SELECT * FROM ticket_history WHERE history_type_id=23' )->fetchAll( \PDO::FETCH_ASSOC );

		$this->assertEquals( [
			[
				'id' => '1',
				'name' => '%%Address Checker%%99',
				'history_type_id' => '23',
				'ticket_id' => '1',
				'article_id' => null, 
				'type_id' => '1',
				'queue_id' => '5',
				'owner_id' => '99',
				'priority_id' => '3',
				'state_id' => '1',
				'create_time' => '2017-01-20 14:00:00',
				'create_by' => '99',
				'change_time' => '2017-01-20 14:00:00',
				'change_by' => '99',
			],
		], $history );
	}

	private function createDbSchema() {
		$this->db->exec( file_get_contents( __DIR__ . '/../db_schema/schema.sql' ) );
	}

	private function insertInitialData() {
		$initialData = include( __DIR__ . '/../data/db_datasets/initial.php' );
		foreach( $initialData as $table => $rows ) {
			foreach( $rows as $row ) {
				$this->db->insert( $table, $row );
			}
		}
	}

}