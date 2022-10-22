<?php

namespace Bra\core\view;

use Bra\core\Config;
use Bra\core\Holder;
use eftec\bladeone\BladeOne;
/**
 * Class BraView
 * @mixin BladeOne
 */
class View extends BladeOne{

	public function get_shared (): array {
		return $this->variablesGlobal;
	}


	public function compile_blade_arr (array $tpl_arr, array $params = []): array {
		return array_map(function ($tpl) use ($params) {
			if (is_array($tpl)) {
				return $this->compile_blade_arr($tpl, $params);
			} else {
				return $this->runString($tpl ?? '', $params ?? []);
			}
		}, $tpl_arr);
	}

	public function run($view = null, $variables = []): string{
		return parent::run($view , $variables);
	}
}
