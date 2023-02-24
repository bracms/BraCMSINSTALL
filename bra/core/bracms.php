<?php

use Bra\core\route\BraRoute;
use Illuminate\Container\Container;
use JetBrains\PhpStorm\NoReturn;
use Monolog\Handler\StreamHandler;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

function ico($name) {
    try {
        return Container::getInstance()->get($name);
    } catch (NotFoundExceptionInterface|ContainerExceptionInterface $e) {
        return false;
    }
}

function config($key) {
    return is_null($key) ? ico('config')->items : ico('config')->get($key);
}

function make_url($route, $params = [], $mapping = [], $domain = null): string {
    return BraRoute::make_url($route, $params, $mapping, $domain);
}

/**
 * code = 1 means everything goes normal,
 * if code is a number , http status code will always be 200
 * you can use array for the first param,
 * [1 , 200] means the code went nothing wrong,http status code 200
 * [403] means something happened,and http status code will be 403 too
 * [403 , 500] means something happened,and http status code will be 500
 * @param $code
 * @param string $msg
 * @param string $url
 * @param array $data
 * @param string $js
 * @return array
 */
function bra_res($code, string $msg = '', string $url = '', mixed $data = [], mixed $js = ''): array {
    if (is_array($code)) {
        $exec_code = $code[0];
        $status_code = $code[1] ?? $code[0];
    } else {
        $exec_code = $code;
    }
    $ret['code'] = $exec_code;
    if ($msg) {
        $ret['msg'] = $msg;
    }
    if ($url) {
        $ret['url'] = $url;
    }
    if (isset($data)) {
        $ret['data'] = $data;
    }
    if ($js) {
        $ret['js'] = $js;
    }
    $ret['status_code'] = $status_code ?? 200;
    return $ret;
}

function yearly_log($level, $message, $context) {
    bra_time_log("yearly", $level, $message, $context);
}

function monthly_log($level, $message, $context) {
    bra_time_log("monthly", $level, $message, $context);
}

function daily_log($message, $level = '', $context = [], $chl = 'bracms') {
    bra_time_log("daily", $level, $message, $context, $chl);
}

function hour_log($message, $level = '', $context = [], $chl = 'bracms') {
    bra_time_log("hourly", $level, $message, $context, $chl);
}

function minutely_log($message, $level = '', $context = [], $chl = 'bracms') {
    bra_time_log("minutely", $level, $message, $context, $chl);
}

function secondly_log($message, $level = '', $context = [], $chl = 'bracms') {
    bra_time_log("secondly", $level, $message, $context, $chl);
}

function bra_time_log($time, $level, $message, $context = [], $chl = 'bracms') {
    $level = $level ?: config("log.monolog.level");
    $file_name = match ($time) {
        "yearly" => date("Y"),
        "monthly" => date("Y-m"),
        "daily" => date("Y-m-d"),
        "hourly" => date("Y-m-d H"),
        "minutely" => date("Y-m-d H:i"),
        "secondly" => date("Y-m-d H:i:s"),
    };
    # chl , level , time
    $path = sprintf(config('log.monolog.path_prefix'), $chl, $level, $time . "_" . $file_name);
    bra_log($message, $level, $context, $chl, $path);
}

function bra_log($message, $level = '', $context = [], $chl = '', $path = '') {
    static $loggers = [];
    if (!isset($loggers[$chl])) {
        $loggers[$chl] = ico('logger')->withName($chl);
    }
    $path = $path ?: config("log.monolog.path");
    $level = $level ?: config("log.monolog.level");
    $stream_handler = new StreamHandler($path, $level);
    $loggers[$chl]->setHandlers([$stream_handler]);
    $loggers[$chl]->log($level, $message, $context);
}

function local_path($path = ''): string {
    if ($path) {
        return BRA_PATH . DS . $path . DS;
    } else {
        return BRA_PATH . DS;
    }
}

#[NoReturn] function abort($bra_res, $type = '', $headers = []) {

    hour_log(json_encode($bra_res), chl: 'abort');
    ico('app')->abort($bra_res, $type, $headers);
}

function ico_make($alias, $class) {
    if (!ico($alias)) {
        return ico('ico')->instance($alias, ico('ico')->make($class));
    }
}

function trans($key = null, $replace = [], $locale = null) {
    return $key;
}

function lang($key = null, $replace = [], $locale = null) {
    return $key;
}


function bra_asset($theme , $device, $module_sign, $file_name, $secure = null)
{
    $path = 'themes/' . $theme . '/' . $device . '/' . $module_sign . '/' . $file_name;

    return asset($path, $secure);
}


/**
 * Generate an asset path for the application.
 *
 * @param string $path
 * @return string
 */
function asset (string $path , $host = ''): string {
    $suffix = '';
    if (config('app.debug')) {
        $suffix = time();
    }

    return $host . config('view.asset_root') . ltrim($path, "/") . "?v=" . ico('app')->get_version() . $suffix;
}