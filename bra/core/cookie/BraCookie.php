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
namespace Bra\core\cookie;

use Bra\core\http\BraRequest;
use Symfony\Component\HttpFoundation\Cookie;

class BraCookie {
	public static array $queued_cookies = [];

	public static function queue ($key, $value , int $expire =  86400 * 365 * 10) {
		self::$queued_cookies[$key] = Cookie::create($key , $value , time() + $expire);
	}

	public static function get ($key): float|bool|int|string|null {
		return BraRequest::$holder->cookies->get($key);
	}
}
