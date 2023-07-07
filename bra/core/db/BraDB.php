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
namespace Bra\core\db;

use Bra\core\Holder;
use Illuminate\Database\Connection;

/**
 * Class BraDB
 * @mixin DB | Connection
 */
class BraDB extends Holder {

	public static DB $holder;

	public function __construct () {
		self::$holder = new DB();
	}

	public static function __callStatic (string $name, array $arguments) {
		return self::$holder->getConnection()->$name(...$arguments);
	}

	public static function table_exist (string $table_name, $connect = null): bool {
		return self::$holder->schema($connect)->hasTable($table_name);
	}

    public static function add_connection(string $config_name)
    {
        self::$holder->addConnection(config('database.connections')[$config_name] , $config_name);
    }

}
