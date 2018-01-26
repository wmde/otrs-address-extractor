<?php

declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress\Domain;

/**
 * @license GNU GPL v2+
 * @author Gabriel Birke < gabriel.birke@wikimedia.de >
 */
class UniqueIdFinder {

	const MAX_WORD_VICINITY = 4;

	private const MEMBER_MATCH_RX = '/Mitglieds?(:?nr|nummer)/i';
	private const ADDRESS_MATCH_RX = '/Add?res?s(:?nr|nummer)/i';

	public function findIds( string $text ): array {
		$ids = [];
		$words = preg_split( '/\s+/', $text );
		foreach ( $words as $idx => $word ) {
			if ( is_numeric( $word ) ) {
				$ids[] = $this->extractIdOrNull( $idx, $words );
			}
		}

		return array_values( array_filter( $ids ) );
	}

	private function extractIdOrNull( $idx, array $words ): ? UniqueId {
		$start = max( 0, $idx - self::MAX_WORD_VICINITY );
		$end = min( count( $words ), $idx + self::MAX_WORD_VICINITY );
		for ( $i = $start; $i < $end; $i++ ) {
			if ( $i === $idx ) {
				continue;
			}
			if ( preg_match( self::MEMBER_MATCH_RX, $words[$i] ) ) {
				return UniqueId::newMembershipNumber( (int) $words[$idx] );
			} elseif ( preg_match( self::ADDRESS_MATCH_RX, $words[$i] ) ) {
				return UniqueId::newAddressNumber( (int) $words[$idx] );
			}
		}
		return null;
	}
}