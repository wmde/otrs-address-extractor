<?php

declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress\Domain;

/**
 * @license GNU GPL v2+
 * @author Gabriel Birke < gabriel.birke@wikimedia.de >
 */
class UniqueId {

	const TYPE_MEMBER  = 'Mitgliedsnummer';
	const TYPE_ADDRESS = 'Adressnummer';

	private $type;
	private $id;

	public function __construct( string $type, int $id ) {
		$this->type = $type;
		$this->id = $id;
	}

	public function getType(): string {
		return $this->type;
	}

	public function getId(): int {
		return $this->id;
	}

	public static function newMembershipNumber( int $id ) {
		return new self( self::TYPE_MEMBER, $id );
	}

	public static function newAddressNumber( int $id ) {
		return new self( self::TYPE_ADDRESS, $id );
	}
}