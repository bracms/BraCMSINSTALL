<?php

namespace Bra\core\view;

use Bra\core\Holder;

/**
 * Class BraView
 * @mixin View
 */
class BraView extends Holder {

	public static View $holder;
	public static BraView $self;

	public function __construct () {
		self::$holder = new View(config("view.paths"), config("view.compiled"), View::MODE_DEBUG); // MODE_DEBUG allows to pinpoint troubles.
		self::$self = $this; // MODE_DEBUG allows to pinpoint troubles.
	}

	public static function __callStatic (string $name, array $arguments) {
		if (method_exists(self::class, $name)) {
			return self::$self->$name(...$arguments);
		} else {
			return self::$holder->$name(...$arguments);
		}
	}
}
