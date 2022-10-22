<?php

namespace Bra\core\session;
use Illuminate\Session\SessionManager;

class BraSessionManger extends SessionManager {

	public function __construct () {
		parent::__construct(ico('ico'));
	}
}
