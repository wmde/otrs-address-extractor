<?php

declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress\Test\Fixtures;

use WMDE\OtrsExtractAddress\Domain\SourceData;
use WMDE\OtrsExtractAddress\SourceDataValidationResult;
use WMDE\OtrsExtractAddress\SourceDataValidatorInterface;

class FailingSourceDataValidator implements SourceDataValidatorInterface {

	const ERROR_MESSAGE = 'Failed on purpose';

	public function validate( SourceData $data ): SourceDataValidationResult
	{
		return SourceDataValidationResult::newInvalidResult( self::ERROR_MESSAGE );
	}
}