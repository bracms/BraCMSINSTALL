<?php

namespace Bra\core\http;

use Bra\core\Holder;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BraDB
 * @mixin Request
 */
class BraRequest extends Holder
{

    public static Request $holder;

    public function __construct()
    {
        self::$holder = Request::createFromGlobals();
    }

    public static function get($key = null): float|bool|int|string|array|null
    {
        $all = self::$holder->request->all();
        if (empty($key)) {
            return $all;
        } else {
            return $all[$key];
        }
    }

    public static function set_arr(array $arr)
    {
        foreach ($arr as $k => $v) {
            self::set($k, $v);
        }

    }

    public static function set($key, $val)
    {
        self::$holder->request->set($key, $val);
    }
}
