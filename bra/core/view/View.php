<?php

namespace Bra\core\view;

use Bra\core\Config;
use Bra\core\Holder;
use eftec\bladeone\BladeOne;

/**
 * Class BraView
 * @mixin BladeOne
 */
class View extends BladeOne {

    public function get_shared(): array {
        return $this->variablesGlobal;
    }

    public function get_all(): array {
        return $this->variables;
    }

    public function compile_blade_arr(array $tpl_arr, array $params = []): array {
        return array_map(function ($tpl) use ($params) {
            if (is_array($tpl)) {
                return $this->compile_blade_arr($tpl, $params);
            } else {
                return $this->runString($tpl ?? '', $params ?? []);
            }
        }, $tpl_arr);
    }

    public function run_string($view = null, $variables = []): string {
        return parent::runString($view, $variables);
    }

    public function compile_blade_str($view = null, $variables = []): string {
        return parent::runString($view, $variables);
    }
}
