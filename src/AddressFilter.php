<?php

declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress;

/**
 * @license GNU GPL v2+
 * @author Gabriel Birke < gabriel.birke@wikimedia.de >
 */
class AddressFilter {

	private $excludedAddresses;

	/**
	 * @param Address[] $excludedAddresses
	 */
	public function __construct( array $excludedAddresses = [] ) {
		$this->excludedAddresses = $excludedAddresses;
	}

	public function firstValidAddress( array $addresses ): ?Address {
		$filtered = $this->getValidAddresses( $addresses );
		return array_shift( $filtered );
	}

	public function getValidAddresses( array $addresses ): array {
		$validAddresses = [];
		foreach ( $addresses as $address ) {
			if ( !$address->isValid() ) {
				continue;
			}
			if ( in_array( $address, $this->excludedAddresses ) ) {
				continue;
			}
			$validAddresses[] = $address;
		}
		return $validAddresses;
	}

}