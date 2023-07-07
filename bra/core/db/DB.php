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

use Illuminate\Database\Capsule\Manager;

class DB extends Manager {

	public function __construct () {
		parent::__construct();
		$this->init_db();
	}

    public function init_db()
    {
        $this->addConnection(config('database.connections')[config('database.default')]);
        $this->setAsGlobal();
        $this->bootEloquent();
    }
}
