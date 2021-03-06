<?php

declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress;

use WMDE\OtrsExtractAddress\Domain\SourceData;

/**
 * @license GNU GPL v2+
 * @author Gabriel Birke < gabriel.birke@wikimedia.de >
 */
interface SourceDataValidatorInterface {
	public function validate( SourceData $data ): SourceDataValidationResult;
}