<?php

namespace Bra\core\traits;

trait DataAttrTrait {

	private function setAttr (string|array $keys, $value = null) {
		if (is_array($keys)) {
			foreach ($keys as $k => $v) {
                try {
                    if(str_starts_with('\\' , $k)){
                        dd("setAttr Error" ,$keys, $value );
                    }else{
                        $k = trim( $k );
                        $this->$k = $v;
                    }
                }catch (\Exception $e){
                    dd($keys , $value , $e->getMessage());
                }
			}
		} else {
			$this->$keys = $value;
		}
	}
}
