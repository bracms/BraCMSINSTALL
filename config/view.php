<?php

use eftec\bladeone\BladeOne;

return [
	/**
	 * always compile in debug mode
	 */
	'mode' => env('APP_DEBUG', false) ? BladeOne::MODE_DEBUG : BladeOne::MODE_FAST ,
	/*
	 * the view will render even if the target action is not yet exist.
	 * useful for frontend developer to develop views without the backend developer
	 */
	'allow_only_view' => true ,
	'asset_root' => "/" ,
	/*
	|--------------------------------------------------------------------------
	| View Storage Paths
	|--------------------------------------------------------------------------
	|
	| Most templating systems load templates from disk. Here you may specify
	| an array of paths that should be checked for your views.
	|
	*/
	'paths' => [
		BRA_PATH . DIRECTORY_SEPARATOR . 'bra_views' ,
		BRA_PATH . DIRECTORY_SEPARATOR . 'admin_views'
	],
	/*
	|--------------------------------------------------------------------------
	| Compiled View Path
	|--------------------------------------------------------------------------
	|
	| This option determines where all the compiled Blade templates will be
	| stored for your application. Typically, this is within the storage
	| directory. However, as usual, you are free to change this value.
	|
	*/
	'compiled' => env(
		'VIEW_COMPILED_PATH',
		BRA_PATH . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR . 'views',
	),
];
