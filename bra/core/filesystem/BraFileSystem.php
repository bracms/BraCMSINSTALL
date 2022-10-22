<?php

namespace Bra\core\filesystem;

use Bra\core\Holder;
use Illuminate\Filesystem\Filesystem;

class BraFileSystem extends Holder {
	public static BraFileSystemManger $holder;
	public function __construct () {
		self::$holder = new BraFileSystemManger();

		ico('ico')->instance("files", self::$holder);
	}
}
