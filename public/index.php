<?php
use Bra\core\App;
use Bra\core\Config;
use Bra\core\http\BraRequest;
use Bra\core\log\BraLogger;
use Illuminate\Container\Container;

define("BRA_PATH", dirname(__DIR__));
const DS = DIRECTORY_SEPARATOR;
const PUBLIC_ROOT = __DIR__ ;

require __DIR__ . '/../vendor/autoload.php';

$ico = new Container();
$ico->instance('ico', Container::setInstance($ico));
$ico->instance('request', $ico->make(BraRequest::class)); // config free
$ico->instance('config', $ico->make(Config::class));
$ico->instance('logger', $ico->make(BraLogger::class)); // config free
(new App($ico))->send();

