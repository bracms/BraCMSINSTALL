<?php

use Bra\core\cache\BraCache;
use Bra\core\controller\ControllerMiddleware;
use Bra\core\db\BraDB;
use Bra\core\debug\AfterDebugMiddleware;
use Bra\core\event\EventMiddleware;
use Bra\core\exception\ErrorMiddleware;
use Bra\core\filesystem\BraFileSystem;
use Bra\core\hash\BraHash;
use Bra\core\http\BraCsrfMiddleware;
use Bra\core\http\RequestMiddleware;
use Bra\core\route\RouteMiddleware;
use Bra\core\session\BraSession;
use Bra\core\validation\BraValidation;
use Bra\core\view\BraView;

return [
	'env' => env('APP_ENV', 'production'),
	'debug' => (bool)env('APP_DEBUG', false),
	'locale' => 'en',
    'is_checking' => false,
	'key' => env('APP_KEY'),
	'cipher' => 'AES-256-CBC',
	# the middlewares , the order is important
	"before_debug_middlewares" => [
	],
	"before_middlewares" => [
        RouteMiddleware::class,
		EventMiddleware::class,
		BraCsrfMiddleware::class,
		RequestMiddleware::class,
		ErrorMiddleware::class,
	],
	"middle_middlewares" => [
		ControllerMiddleware::class,
	],
	"after_middlewares" => [
	],
	"after_debug_middlewares" => [
		AfterDebugMiddleware::class
	],
	"event_observers" => [

	],
	'aliases' => [
		'fs' => BraFileSystem::class,
		'session' => BraSession::class,
		'cache' => BraCache::class,
		'hash' => BraHash::class,
		'db' => BraDB::class,
		'view' => BraView::class,
		'validate' => BraValidation::class,
//		----------------------- THIS SHOULD ALWAYS THE FIRST -----------------------------
		'layers' => Bra\core\middleware\MiddlewareLayers::class,
	],
	"extra_files" => [

	]
];
