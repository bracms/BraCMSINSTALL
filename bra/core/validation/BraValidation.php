<?php

namespace Bra\core\validation;

use Bra\core\db\BraDB;
use Bra\core\Holder;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\DatabasePresenceVerifier;
use Illuminate\Validation\Factory;

class BraValidation extends Holder {
	public static Factory $holder;

	public function __construct () {
		$loader = new FileLoader(new Filesystem, 'lang');
		$translator = new Translator($loader, 'en');
		self::$holder = new Factory($translator, ico('ico'));
		$presence = new DatabasePresenceVerifier(BraDB::$holder->getDatabaseManager());
		self::$holder->setPresenceVerifier($presence);
	}

	public static function __callStatic (string $name, array $arguments) {
		return self::$holder->$name(...$arguments);
	}

}
