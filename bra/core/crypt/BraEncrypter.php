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
namespace Bra\core\crypt;

use Bra\core\Holder;
use Illuminate\Database\Connection;
/**
 * Class BraDB
 * @mixin Encrypter
 */
class BraEncrypter extends Holder {

    public static Encrypter $holder;

    public function __construct () {
        self::$holder = new Encrypter(env('APP_KEY'));
    }

    public static function __callStatic (string $name, array $arguments) {
        return self::$holder->$name(...$arguments);
    }

}
