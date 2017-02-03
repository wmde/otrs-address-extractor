<?php

declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress\DataAccess;

/**
 * @license GNU GPL v2+
 * @author Gabriel Birke < gabriel.birke@wikimedia.de >
 */
class TicketNumberReader extends \IteratorIterator {

	const TICKET_NUMBER_COLUMN = 0;

	public function __construct( string $fileName ) {
		$inputFile = new \SplFileObject( $fileName, 'r' );
		$inputFile->setCsvControl( ';', '"' );
		$inputFile->setFlags( \SplFileObject::READ_CSV | \SplFileObject::READ_AHEAD | \SplFileObject::SKIP_EMPTY
			| \SplFileObject::DROP_NEW_LINE );
		parent::__construct( $inputFile );
	}

	public function current(): string {
		$row = $this->getInnerIterator()->current();
		return (string) $row[self::TICKET_NUMBER_COLUMN];
	}

}