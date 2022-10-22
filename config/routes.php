<?php

/**
 * define routes here ,example "route" => [] , if level is set to 0 ,route will not be checked
 */
$web_routes = [
];

$api_routes = [

];

/**
 * strict_level it is number , 0 means no strict , any route will be processed
 * 1 means match module
 * 2 means match module controller
 * 3 means match module controller action
 * full means math the exact string of the route
 */
return [
	'web' => $web_routes,
	'api' => $api_routes,
	'route_strict_level' => 1,
];
