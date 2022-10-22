<?php
namespace Bra\core\exception;

use Bra\core\middleware\Middleware;
use Illuminate\Container\Container;

class ErrorMiddleware extends Middleware {

	function handle () {
		error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
		set_exception_handler(function (\Throwable $e){
			$data['trace'] = $e->getTrace();
			$data['file'] = $e->getFile();
			$data['line'] = $e->getLine();
			/**
			 * Pis off the web debugger for error message
			 */
			abort(bra_res([$e->getCode() , ico('request')->isXmlHttpRequest()?200:500], $e->getMessage() , '' , $data));

		});
	}

}
