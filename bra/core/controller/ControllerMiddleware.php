<?php
// +----------------------------------------------------------------------
// | 鸣鹤CMS [ New Better  ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2017 http://www.bracms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( 您必须获取授权才能进行商业使用 )
// +----------------------------------------------------------------------
// | Author: new better <1620298436@qq.com>
// +----------------------------------------------------------------------
namespace Bra\core\controller;

use Bra\core\http\BraRequest;
use Bra\core\middleware\Middleware;
use Illuminate\Container\Container;
use Illuminate\Support\Str;

class ControllerMiddleware extends Middleware {


	function handle () {
		if(isset($this->app->page_data)){
			throw new \Exception("The app data should only be given once!");
		}
		ico('app')->page_data  = $this->base_data();
	}

	private function base_data () {
		$action_sign = join('_', [ROUTE_M, ROUTE_C, ROUTE_A]);
		if (!preg_match('/^[\x{4e00}-\x{9fa5}_0-9a-z]{1,50}$/iu', $action_sign)) {
			abort(bra_res([40401 , 404], 'Action Sign NOT FOUND'));
		}
		$class = Str::studly(ROUTE_C . "_page");
		$pager_name = "\\Bra\\" . ROUTE_M . "\\pages\\$class";
		if (!class_exists($pager_name)) {
			if (config('allow_only_view.allow_view')) {
				return T();
			} else {
				abort(bra_res([40402 , 404],  $pager_name . " NOT FOUND"));
			}
		} else {
			//menu
			$query = BraRequest::$holder->request->all();
			$container = Container::getInstance();
			$bra_page = new $pager_name();
			if(method_exists($bra_page , "_init_")){
				$bra_page->_init_($query);
			}
			#is the data is created before action happens
			#we will stop here
			if (isset($bra_page->page_data)) {
				return $bra_page->page_data;
			}



			if (!method_exists($pager_name, $action_sign)) {
				if (config('allow_only_view.allow_view')) {
					$page_data = T();
				} else {
					abort(bra_res([40403 , 404], $pager_name . "@" . $action_sign));
				}
			} else {
				$page_data = $container->call([$bra_page, $action_sign] , ['query' => $query]);
			}

			return $page_data ?? "";
		}
	}

}
