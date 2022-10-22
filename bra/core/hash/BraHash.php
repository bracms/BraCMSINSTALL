<?php

namespace Bra\core\hash;

use Bra\core\Holder;
use Illuminate\Hashing\BcryptHasher;

/**
 * Class BraView
 * @mixin BcryptHasher
 */
class BraHash extends Holder {

	public static BcryptHasher $holder;

	public function __construct () {
		self::$holder = new BcryptHasher();
	}

	public static function __callStatic (string $name, array $arguments) {
		return self::$holder->$name(...$arguments);
	}

}
