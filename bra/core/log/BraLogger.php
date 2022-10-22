<?php

namespace Bra\core\log;

use Bra\core\Holder;
use Monolog\Logger;

/**
 * Class BraView
 * @mixin Logger
 */
class BraLogger extends Holder {

	protected static Logger $holder;

	public function __construct () {
		self::$holder = new Logger("info");
	}

}
