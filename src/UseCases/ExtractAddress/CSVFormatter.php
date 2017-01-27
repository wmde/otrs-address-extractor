<?php
declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress\UseCases\ExtractAddress;

trait CSVFormatter {

	protected function formatAsCSV( array $row ): string {
		return implode( ';', array_map( function( $v ) {
			if ( $v ) {
				return '"'. str_replace( '"', '""', $v ) . '"';
			} else {
				return '';
			}
		}, $row ) ) . "\n";
	}
}