<?php
declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress\UseCases\ExtractAddress;

class CSVFormatter {

	public function formatAsCSV( array $row ): string {
		return implode( ';', array_map( function( $v ) {
			if ( $v ) {
				return '"'. str_replace( '"', '""', $v ) . '"';
			}
			return '';
		}, $row ) ) . "\n";
	}
}