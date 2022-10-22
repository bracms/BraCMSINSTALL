<?php

namespace Bra\core\session;

use Bra\core\cache\BraCache;
use Bra\core\Holder;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;

class BraSession extends Holder {

	public static Session $holder;

	public function __construct () {
		self::$holder = new Session(new PhpBridgeSessionStorage());
		session_start();
		self::$holder->start();
	}

	public static function get ($key) {
		return self::$holder->get($key);
	}

	public static function set ($key, $val) {
		self::$holder->set($key, $val);
	}

}
