<?php

namespace Bra\core\route;

use Bra\core\http\BraRequest;
use Bra\core\objects\BraArray;
use Bra\core\objects\BraString;

class BraRoute {

	public static function make_url ($route, $params = [], $mapping = [], $domain = null): string {
		if (count(explode('/', ltrim($route, "/"))) > 3 && count(array_filter(explode('/', ltrim($route, "/")))) % 2 != 1) {
			abort(403, 'params not allowed for ' . $route);
		}
		if (!is_array($params)) {
			$new_vars = [];
			$vars = explode("&", $params);
			foreach ($vars as $var) {
				$var = explode("=", $var);
				$new_vars[$var[0]] = $var[1];
			}
		} else {
			$new_vars = $params;
		}
		if ($mapping) {
			foreach ($new_vars as &$new_var) {
				$new_var = BraString::parse_param_str($new_var, $mapping);
			}
		}
		$new_vars = BraArray::array_to_query($new_vars);
		if ($new_vars) {
			$url = "/" . ltrim($route, "/") . '?' . $new_vars;
		} else {
			$url = "/" . ltrim($route, "/");
		}
		//Generate URL
		if ($domain) {
			return str_replace(BraRequest::$holder->getHost(), $domain, $url);
		}

		return $url;
	}

	public static function get_url ($path, $params = []): string {
		$new_vars = "";
		if ($params) {
			$new_vars = "?" . BraArray::array_to_query($params);
		}

		return BraRequest::$holder->getSchemeAndHttpHost() . DS . $path . $new_vars;
	}
}
