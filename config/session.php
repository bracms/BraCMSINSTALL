<?php

return [
	'lifetime' => 120,
	'lottery' => [2, 100],
	'expire_on_close' => false,
	'cookie' => 'bra_session',
	'path' => '/',
	'domain' => null,
	'driver' => 'file',
	'files' => local_path('storage'.DS .'sessions' ) ,
];
