<?php

declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress\DataAccess;

use WMDE\OtrsExtractAddress\Domain\Address;
use WMDE\OtrsExtractAddress\Domain\UniqueId;
use WMDE\OtrsExtractAddress\UseCases\ExtractAddress\ExtractedData;

/**
 * @license GNU GPL v2+
 * @author Gabriel Birke < gabriel.birke@wikimedia.de >
 */
class RowToExtractedDataConverter extends \IteratorIterator {

	public function current(): ExtractedData {
		$row = parent::current();
		return new ExtractedData(
			(int) $row[0],
			$row[1],
			$row[2],
			new Address( $row[5], $row[6], $row[7] ),
			$this->getUniqueId( $row[3], (int) $row[4] )
		);
	}

	private function getUniqueId( string $type, int $id ): UniqueId {
		switch ( $type ) {
			case UniqueId::TYPE_ADDRESS:
				return UniqueId::newAddressNumber( $id );
			case UniqueId::TYPE_MEMBER:
				return UniqueId::newMembershipNumber( $id );
			default:
				throw new \RuntimeException( 'Unknown address type: ' . $type );
		}
	}

}