<?php

namespace Bra\core\middleware;

use Bra\core\Config;
use Closure;

class MiddlewareLayers {
	protected Closure $start;

	public function __construct () {
		$this->start = function () {
		};

		$this->stack_middleware()->handle();
	}

	public function stack_middleware (): static {
		foreach (config('app.after_middlewares') as $class) {
			$this->add_mid_layer(new $class());
		}
		if (config('app.debug')) {
			foreach (config('app.after_debug_middlewares') as $class) {
				$this->add_mid_layer(new $class());
			}
		}
		foreach (config('app.middle_middlewares') as $class) {
			$this->add_mid_layer(new $class());
		}
		foreach (config('app.before_middlewares') as $class) {
			$this->add_mid_layer(new $class());
		}
		if (config('app.debug')) {
			foreach (config('app.before_debug_middlewares') as $class) {
				$this->add_mid_layer(new $class());
			}
		}

		return $this;
	}

	public function add_mid_layer (Middleware $middleware): static {
		$this->add($middleware);

		return $this;
	}

	public function add (Middleware $middleware) {
		$next = $this->start;
		$this->start = function () use ($middleware, $next) {
			return $middleware($next);
		};
	}

	public function handle () {
		return call_user_func($this->start);
	}
}
