<?php
use Monolog\Handler\StreamHandler;

return [
	'monolog' => [
		'handler' => StreamHandler::class,
		'level' => 'debug' ,
		'path_prefix' => BRA_PATH . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR. '%s' .  DIRECTORY_SEPARATOR . '%s_%s_bracms.log',
		'path' => BRA_PATH . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'bracms.log',
	]
];
