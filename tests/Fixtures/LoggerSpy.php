<?php
declare( strict_types = 1 );

namespace WMDE\OtrsExtractAddress\Test\Fixtures;

use Psr\Log\AbstractLogger;

/**
 * @license GNU GPL v2+
 * @author Gabriel Birke < gabriel.birke@wikimedia.de >
 */
class LoggerSpy extends AbstractLogger {

	private $logs = [];

	public function log( $level, $message, array $context = [] ) {
		$this->logs[] = [ $level, $message, $context ];
	}

	public function getLogs(): array {
		return $this->logs;
	}

}