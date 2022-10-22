<?php

namespace Bra\core\debug;

use Bra\core\middleware\Middleware;

class AfterDebugMiddleware extends Middleware {

	function convert ($size): string {
		$unit = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');

		return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 4) . ' ' . $unit[$i];
	}

	function handle () {
		bra_log($this->convert(memory_get_usage(true)));
		bra_log(memory_get_usage(true));
	}
}
