<?php


namespace WMDE\OtrsExtractAddress;

interface SourceDataReader {

	public function hasMoreRows(): bool;
	public function getRow(): SourceData;

}