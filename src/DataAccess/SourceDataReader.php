<?php

declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress\DataAccess;

use WMDE\OtrsExtractAddress\Domain\SourceData;

interface SourceDataReader {

	public function hasMoreRows(): bool;
	public function getRow(): SourceData;

}