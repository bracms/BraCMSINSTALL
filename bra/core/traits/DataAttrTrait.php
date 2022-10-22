<?php

namespace Bra\core\traits;

trait DataAttrTrait {

	private function setAttr (string|array $keys, $value = null) {
		if (is_array($keys)) {
			foreach ($keys as $k => $v) {
				$this->$k = $v;
			}
		} else {
			$this->$keys = $value;
		}
	}
}
