<?php

declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress;

/**
 * Look for 5-digit postcode and extract text around them as address
 *
 * @license GNU GPL v2+
 * @author Gabriel Birke < gabriel.birke@wikimedia.de >
 */
class AddressFinder {

	const POSTCODE_LENGTH = 5;

	private $excludedAddresses;

	/**
	 * @param Address[] $excludedAddresses
	 */
	public function __construct( array $excludedAddresses = [] ) {
		$this->excludedAddresses = $excludedAddresses;
	}

	public function findAddress( string $text ): ?Address {
		if ( !preg_match_all( '/\d{' . self::POSTCODE_LENGTH . '}/', $text, $matches, PREG_OFFSET_CAPTURE ) ) {
			return null;
		}
		foreach( $matches[0] as $match ) {
			$address = $this->extractAddress( $text, $match[1] );
			if ( !$address->isValid() ) {
				continue;
			}
			if ( in_array( $address, $this->excludedAddresses ) ) {
				continue;
			}
			return $address;
		}
		return null;
	}

	private function extractAddress( $text, $postcodePosition ): Address {
		$postcode = substr( $text, $postcodePosition, self::POSTCODE_LENGTH );

		return new Address(
			$this->extractStreet( $text, $postcodePosition ),
			$postcode,
			$this->extractCity( $text, $postcodePosition)
		);
	}

	private function extractStreet( string $text, int $postcodeStart ): string {
		$streetStart = strrpos( substr( $text, 0, $postcodeStart - 1 ), "\n" );
		if ( $streetStart === false ) {
			$streetStart = 0;
		}
		$street = substr( $text, $streetStart, $postcodeStart - $streetStart );
		return preg_replace( '/,$/', '', trim( $street ) );
	}

	private function extractCity( string $text, int $postcodeStart ): string {
		$cityStart = $postcodeStart + self::POSTCODE_LENGTH;
		$cityEnd = strpos( $text, "\n", $cityStart );
		$city = substr( $text, $cityStart, $cityEnd - $cityStart );
		return trim( $city );
	}
}