<?php
namespace WMDE\OtrsExtractAddress;


/**
 * @license GNU GPL v2+
 * @author Gabriel Birke < gabriel.birke@wikimedia.de >
 */
interface SourceDataValidatorInterface {
	public function validate( SourceData $data ): SourceDataValidationResult;
}