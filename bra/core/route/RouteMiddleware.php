<?php

namespace Bra\core\route;

use Bra\core\middleware\Middleware;

class RouteMiddleware extends Middleware {

	function handle () {
		$this->process_route(config('routes'));
	}

	private function process_route ($routes) {
		$web_routes = $routes['web'];
		switch (config('routes.route_strict_level')) {
			case 0:
				break;
			case 1:
				if (!isset($web_routes[ROUTE_M])) {
					abort(bra_res([40405, 404], 'Route not found for module ' . ROUTE_M));
				}
				break;
			case 2:
				if (!isset($web_routes[join("/", [ROUTE_M, ROUTE_C])])) {
					abort(bra_res([40406, 404], 'Route not found for controller' . join("/", [ROUTE_M, ROUTE_C])));
				}
				break;
			case 3:
				if (!isset($web_routes[join("/", [ROUTE_M, ROUTE_C, ROUTE_A])])) {
					abort(bra_res([40407, 404], 'Route not found for action' . join("/", [ROUTE_M, ROUTE_C, ROUTE_A])));
				}
				break;
			default:
				abort(bra_res([500], 'Unknown route_strict_level'));
		}
	}

}
