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
	const POSTCODE_MATCHER = <<<RGX
/
	(?<=\D)  # postcode digits must be preceded by a non-digit
	\d{%d}   # postcode digits, with a placeholder where POSTCODE_LENGTH will be inserted 
	(?=\D)   # postcode digits must be followed by a non-digit (to ensure distinction between other numbers)
/x
RGX;

	public function findAddresses( string $text ): array {
		if ( !preg_match_all( sprintf( self::POSTCODE_MATCHER, self::POSTCODE_LENGTH ), $text, $matches, PREG_OFFSET_CAPTURE ) ) {
			return [];
		}
		$addresses = [];
		foreach ( $matches[0] as $match ) {
			$addresses[] = $this->extractAddress( $text, $match[1] );
		}
		return $addresses;
	}

	private function extractAddress( $text, $postcodePosition ): Address {
		$postcode = substr( $text, $postcodePosition, self::POSTCODE_LENGTH );

		return new Address(
			$this->extractStreet( $text, $postcodePosition ),
			$postcode,
			$this->extractCity( $text, $postcodePosition )
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
		if ( $cityEnd === false ) {
			$cityEnd = strlen( $text );
		}
		$city = substr( $text, $cityStart, $cityEnd - $cityStart );
		return trim( $city );
	}
}