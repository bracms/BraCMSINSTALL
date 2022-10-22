<?php

namespace Bra\core\middleware;

abstract class Middleware{

	public function __invoke (callable $next){
		$this->handle();
		return $next();
	}

	abstract function handle ();
}
