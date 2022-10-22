<?php

namespace Bra\core\event;

use Bra\core\middleware\Middleware;

class EventMiddleware extends Middleware {

	function handle () {
		foreach (config('app.event_observers') as $class) {
			$obj = new $class();
			foreach ($obj->events as $event) {
				ico('app')->listen($obj, $event);
			}
		}
	}

}
