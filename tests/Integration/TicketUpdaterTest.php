<?php

declare(strict_types = 1);

namespace WMDE\OtrsExtractAddress\Test\Integration;
use WMDE\OtrsExtractAddress\DataAccess\TicketUpdater;

/**
 * @license GNU GPL v2+
 * @author Gabriel Birke < gabriel.birke@wikimedia.de >
 */
class TicketUpdaterTest extends \PHPUnit_Extensions_Database_TestCase {

	const CHANGED_USER_ID = 99;

	/**
	 * @var \PDO
	 */
	static private $pdo = null;

	private $conn = null;

	protected function getConnection()
	{
		if ($this->conn === null) {
			if (self::$pdo == null) {
				self::$pdo = new \PDO( 'sqlite::memory:' );
				$this->createDbSchema();
			}
			$this->conn = $this->createDefaultDBConnection( self::$pdo, ':memory:' );
		}

		return $this->conn;
	}

	protected function getDataSet() {
		return new \PHPUnit_Extensions_Database_DataSet_YamlDataSet(
			__DIR__ . '/../data/db_datasets/initial.yml'
		);
	}

	public function testTicketIsChanged() {
		$tickerUpdater = new TicketUpdater( self::$pdo, self::CHANGED_USER_ID );
		$tickerUpdater->updateTicket( 2017011975011968, new \DateTime( '2017-01-20 14:00:00' ) );

		$queryTable = $this->getConnection()->createQueryTable(
			'ticket', 'SELECT * FROM ticket'
		);

		$expectedTable = $this->createYamlDataSet( 'ticket_expected.yml' )
			->getTable( 'ticket' );
		$this->assertTablesEqual( $expectedTable, $queryTable );
	}

	private function createDbSchema() {
		foreach ( glob( __DIR__ . '/../db_schema/*.sql') as $table ) {
			self::$pdo->exec( file_get_contents( $table ) );
		}
	}

	protected function createYamlDataSet( string $name ): \PHPUnit_Extensions_Database_DataSet_AbstractDataSet {
		return new \PHPUnit_Extensions_Database_DataSet_YamlDataSet(
			__DIR__ ."/../data/db_datasets/$name"
		);
	}

}