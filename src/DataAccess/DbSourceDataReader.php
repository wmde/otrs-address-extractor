<?php

declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress\DataAccess;

use WMDE\OtrsExtractAddress\Domain\SourceData;

/**
 * @license GNU GPL v2+
 * @author Gabriel Birke < gabriel.birke@wikimedia.de >
 */
class DbSourceDataReader implements SourceDataReader {

	private $db;

	/**
	 * @var \PDOStatement
	 */
	private $result;
	private $currentRow;
	private $numRows = 0;
	private $startTime;
	private $endTime;

	public function __construct( \PDO $db, \DateTime $startTime, \DateTime $endTime ) {
		$this->db = $db;
		$this->startTime = $startTime;
		$this->endTime = $endTime;
	}

	public function hasMoreRows(): bool {
		if ( $this->numRows === 0 ) {
			$this->selectRows();
		}
		return $this->currentRow < $this->numRows - 1;
	}

	public function getRow(): SourceData {
		$row = $this->result->fetch();
		if ( $row === false ) {
			throw new \RuntimeException( 'Tried to fetch nonexistent row' );
		}
		$this->currentRow++;
		return new SourceData(
			(int) $row['id'],
			(int) $row['tn'],
			(string) $row['email'],
			(string) $row['title'],
			(string) $row['body']
		);
	}

	private function selectRows() {
		$queryParams = [
			'start_time' => $this->startTime->format( 'Y-m-d H:i:s' ),
			'end_time' => $this->endTime->format( 'Y-m-d H:i:s' ),
		];
		$stmt = $this->db->prepare( $this->getQuery( 'COUNT(t.id)' ) );
		$stmt->execute( $queryParams );
		$this->numRows = $stmt->fetchColumn();
		$this->result = $this->db->prepare( $this->getQuery( ' t.id, t.tn, t.title, t.customer_id AS email, a.a_body AS body' ) );
		$this->result->execute( $queryParams );
	}

	private function getQuery( string $columns ): string {
		return <<<SQL
SELECT $columns FROM ticket t JOIN article a ON a.ticket_id = t.id
WHERE t.create_time BETWEEN :start_time AND :end_time
AND t.queue_id IN (5,6) 
AND t.ticket_state_id IN (1,4)
SQL;

	}

}