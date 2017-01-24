<?php

declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress\Test\Fixtures;

use WMDE\OtrsExtractAddress\SourceData;
use WMDE\OtrsExtractAddress\SourceDataValidationResult;
use WMDE\OtrsExtractAddress\SourceDataValidatorInterface;

/**
 * @license GNU GPL v2+
 * @author Gabriel Birke < gabriel.birke@wikimedia.de >
 */
class SucceedingSourceDataValidator implements SourceDataValidatorInterface {
	public function validate( SourceData $data ): SourceDataValidationResult {
		return SourceDataValidationResult::newValidResult( $data->getAddresses()[0] );
	}
}