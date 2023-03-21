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
namespace Bra\core;

use Bra\core\cookie\BraCookie;
use Bra\core\event\EventListenerTrait;
use Exception;
use Illuminate\Container\Container;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class App {
	const version = "2022.0919.01";
	use EventListenerTrait;

	public mixed $page_data;

	function get_version(): string {
		return self::version;
	}
	public function __construct (public Container $ico) {
		$this->ico->instance('app', $this);
		$this->load_extra_files();
		$this->register();
	}

	public function load_extra_files () {
		$files = config("app.extra_files") ?? [];
		foreach ($files as $file){
			require_once $file;
		}
	}

	public function register () {
		$aliases = ico('config')->get('app.aliases');
		foreach ($aliases as $alias => $class) {
			$this->ico->instance($alias, $this->ico->make($class));
		}
	}

    public function run() {
        ico("layers")->stack_middleware()->handle();
        return $this;
    }

	#[NoReturn] public function abort (array $bra_res, $type = '', $headers = [] ) {
		$status_code = $bra_res['status_code'];
		unset($bra_res['status_code']);
		if (!ico('request')->isXmlHttpRequest() && $type !== 'json') {
			if ($bra_res['code'] == 1) {
				$tpl = "bra_ok";
			} else {
				if ($status_code != 200 && config("app.debug")) {
					$tpl = "bra_exception";
				} else {
					$tpl = "bra_error";
				}
			}
			$view_data['code'] = $bra_res['code'] ?? "Error";
			$view_data['msg'] = $bra_res['msg'] ?? "";
			$view_data['url'] = $bra_res['url'] ?? "";
			$view_data['data'] = $bra_res['data'] ?? "";
			$this->page_data = ico('view')->share($view_data)->run("public.common.$tpl", $view_data);
		} else {
			$this->page_data = $bra_res;
		}
		$this->send($status_code, $headers);
		exit();
	}

	/**
	 * @throws Exception
	 */
	public function send ($status_code = 200, $headers = []) {
		if (!ico('request')->isXmlHttpRequest()) {
			$response = new Response();
		} else {
			$response = new JsonResponse();
		}
		foreach (BraCookie::$queued_cookies as $cookie) {
			$response->headers->setCookie($cookie);
		}
		$response->setStatusCode($status_code);
		$response->headers->set("Access-Control-Allow-Origin" , '*');
        $response->headers->set("Access-Control-Allow-Headers" , 'Origin, X-Requested-With, Content-Type, Accept, Authorization');

		$response->setContent(is_string($this->page_data) ? $this->page_data : json_encode($this->page_data));
		$response->send();
	}

}
