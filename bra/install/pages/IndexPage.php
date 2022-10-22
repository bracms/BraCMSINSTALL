<?php

namespace Bra\install\pages;

use Bra\core\cache\BraCache;
use Bra\core\db\BraDB;
use Bra\core\http\BraRequest;
use Bra\core\objects\BraString;
use Bra\install\objects\BraCurl;
use Bra\install\objects\BraFS;
use Bra\install\objects\FileSync;
use Bra\install\objects\ModelSyncInstall;
use Exception;
use Illuminate\Support\Str;

error_reporting(E_ALL & !E_WARNING);

class  IndexPage
{
    public mixed $current_step;
    public bool $check_error = false;

    public function _init_($query): void
    {
        define("API_URL", "https://www.bracms.com/");
        if(file_exists(local_path('config') . "install.lock")){
            abort(bra_res(500 , 'install lock File exist'));
        }
    }

    public function install_index_index($query)
    {
        return ico('view')->run("install.index");
    }

    public function install_index_check($query)
    {
        $data['env'] = $this->checkNnv();
        $data['dir'] = $this->checkDir();
        $data['func'] = $this->checkFunc();
        $data['check_error'] = $this->check_error;
        return ico('view')->run("install.check", $data);
    }

    public function install_index_auth_code($query)
    {
        if (BraRequest::$holder->isMethod("POST")) {
            $config = [
                'domain' => BraRequest::$holder->getHttpHost(),
                'product_licence_code' => trim($query["product_licence_code"]),
                'product_sign' => "bracms"
            ];
            $licence_info = $this->_check_licence($config);
            if ($licence_info['code'] == 1) {
                BraFS::write_config('licence', $config);
                return bra_res(1, 'OK', make_url('install/index/set_config'));
            } else {
                return $licence_info;
            }
        } else {
            return ico('view')->run("install.auth_code");
        }
    }

    public function install_index_save_db($query)
    {
        $query['type'] = 'mysql';
        $database = $query['database'];
//			DB_HOST=127.0.0.1
//			DB_PORT=3306
//			DB_DATABASE=
//			DB_USERNAME=
//			DB_PASSWORD=
        if (!$database['DB_HOST']) {
            return bra_res(500, "请输入主机IP");
        }
        if (!$database['DB_PORT']) {
            return bra_res(500, "请输入端口号");
        }
        if (!$database['DB_DATABASE']) {
            return bra_res(500, "请输入数据库名");
        }
        if (!$database['DB_USERNAME']) {
            return bra_res(500, "请输入用户名");
        }
        if (!$database['DB_PASSWORD']) {
            return bra_res(500, "请输入数据库密码");
        }
        $this->put_env($database);
        return ['code' => 1, 'msg' => '保存成功'];
    }

    public function install_index_check_db($query)
    {
        /**
         * @var $DB BraDB
         */
        $DB = ico('db');
        $this_db = $DB->getConnection();
        $database = $this_db->getConfig();
        try {
            $this_db->query("CREATE DATABASE IF NOT EXISTS `{$database['database']}` DEFAULT CHARACTER SET utf8mb4");
        } catch (Exception $e) {
            return bra_res(503, $e->getMessage());
        }
        try {
            $test = $this_db->select("show databases like '{$database['database']}';");
            if (count($test) == 0) {
                return bra_res(501, "对不起，链接数据库失败，请检查账号权限！");
            }
        } catch (Exception $e) {
            return bra_res(502, $e->getMessage(), "show databases like '{$database['DB_DATABASE']}';");
        }

        if (!BraString::is_username($query['user_name'])) {
            return bra_res(501, "对不起，用户名不合法！");
        }

        if (!BraString::is_password($query['password'])) {
            return bra_res(501, "对不起，密码不合法！");
        }
        BraCache::set('admin_install_info', ['user_name' => $query['user_name'], 'password' => $query['password']]);
        return bra_res(  1,  '数据库连接成功' , make_url('install/index/download_file'));

    }

    public function install_index_set_config($query)
    {
        return ico('view')->run('install.set_config');
    }




    public function action_old($query)
    {
        ModelSyncInstall::$host = $query['server_url'];
        ModelSyncInstall::$pass = $query['password'];
        $action = $query['action'] ?? 'list_models';
        switch ($action) {
            case  'list_models' :
                return bra_res(1, '', '', ModelSyncInstall::get_module_models($query['module_sign']));
                break;
            case  'sync_model' :
                return bra_res(1, '', '', ModelSyncInstall::sync_model(trim($query['table']), 'install'));
            case  'sync_menu' :
                return bra_res(1, '', '', ModelSyncInstall::sync_menu($query['module_sign']));
            case  'sync_roles' :
                return bra_res(1, '', '', ModelSyncInstall::sync_user_roles($query['module_sign']));
            case 'lock_install':
                file_put_contents(config_path() . '/bracms_install.lock', 1);
        }
    }

    public function _check_licence($licence)
    {
        $url = API_URL . 'bracms/product/check_licence';
        $curl = new BraCurl([], 'ajax');
        $response = $curl->fetch($url, 'POST', ['query' => $licence]);
        $body = $response->getBody();
        $content = json_decode($body, 1);
        return $content;
    }

    public function install_index_download_file($query)
    {
        if (BraRequest::$holder->isMethod("POST")) {
            return FileSync::down_file($query['module'], $query['file_path']);
        } else {
//保存用户名//保存密码
            $updater = new FileSync();
            $file_diff = $updater->file_diff("system");
            return ico('view')->run('install.download_file', $file_diff);
        }
    }

    public function install_index_finished() {
        $res = file_put_contents(local_path('bra' . DS . "bra_admin") . 'bra_admin_install.lock', "BRACMS 安装锁定文件");

        return bra_res(1);
    }

    public function install_index_finish()
    {
        $admin_info = BraCache::get('admin_install_info');
        if(empty($admin_info['user_name'])){
            return bra_res(500 , 'Error get install account infos!');
        }
        $admin_user = BraDb::table('user')->find(1);

        $admin_info['role_id'] = 1;
        $admin_info['status'] = 99;
        $admin_info['id'] = 1;
        if (!$admin_user) {
            // create admin account
            $res = D('user')->item_add($admin_info);
        } else {
            $res = D('user')->item_edit($admin_info, 1);
        }

        file_put_contents(local_path('config') . 'install.lock', "BRACMS 安装锁定文件");
        return ico('view')->run('install.finish');
    }

    public function install_index_download_model($query)
    {
        if (BraRequest::$holder->isMethod("POST")) {
            return ModelSyncInstall::sync_install_model($query['table'], $query['module'], 'install');
        } else {
            $res = ModelSyncInstall::get_module_models('system');
            $assigns['sys_tables'] = array_values($res['data']);
            return ico('view')->run('install.download_model', $assigns);
        }
    }

    private function checkNnv()
    {
        $items = [
            'os' => ['操作系统', '不限制', '类Unix', PHP_OS, 'ok'],
            'php' => ['PHP版本', '8.0', '8.0及以上', PHP_VERSION, 'ok'],
            'gd' => ['GD库', '2.0', '2.0及以上', '未知', 'ok'],
        ];
        if ($items['php'][3] < $items['php'][1]) {
            $items['php'][4] = 'no';
            $this->check_error = true;
        }
        $tmp = function_exists('gd_info') ? gd_info() : [];
        if (empty($tmp['GD Version'])) {
            $items['gd'][3] = '未安装';
            $items['gd'][4] = 'no';
            $this->check_error = true;
        } else {
            $items['gd'][3] = $tmp['GD Version'];
        }

        return $items;
    }

    private function checkDir()
    {
        $files_mod = [];
        $chmod_file = "chmod.txt";
        $files = file(local_path('config') . DS . $chmod_file);
        foreach ($files as $_k => $file) {
            $file = str_replace('*', '', $file);
            $file = trim($file);
            $check_target = BRA_PATH . DS . $file;
            if (is_dir($check_target)) {
                $is_dir = '1';
                $cname = '目录';
                //继续检查子目录权限，新加函数
//				$write_able = BraFS::writable_check(SYS_ROOT . $file);
//				if (is_error($write_able)) {
//					$write_able = false;
//				}
            } else {
                $is_dir = '0';
                $cname = '文件';
            }
            //新的判断
            if ($is_dir == '0' && is_writable($check_target)) {
                $is_writable = 1;
            } elseif ($is_dir == '1') {
                $is_writable = BraFS::dir_writeable($check_target);
            } else {
                $is_writable = 0;
            }
            $files_mod[$_k]['file'] = $file;
            $files_mod[$_k]['is_dir'] = $is_dir;
            $files_mod[$_k]['cname'] = $cname;
            $files_mod[$_k]['is_writable'] = $is_writable;
            if (!$is_writable) {
                $this->check_error = true;
            }
        }
        return $files_mod;
    }

    private function checkFunc()
    {
        $items = [
            ['pdo', '支持', 'yes', '类'],
            ['pdo_mysql', '支持', 'yes', '模块'],
            ['fileinfo', '支持', 'yes', '模块'],
            ['curl', '支持', 'yes', '模块'],
//            ['xml', '支持', 'yes', '函数'],
            ['file_get_contents', '支持', 'yes', '函数'],
            ['mb_strlen', '支持', 'yes', '函数'],
            ['gzopen', '支持', 'yes', '函数'],
        ];
        foreach ($items as &$v) {
            if (('类' == $v[3] && !class_exists($v[0])) || ('模块' == $v[3] && !extension_loaded($v[0])) || ('函数' == $v[3] && !function_exists($v[0]))) {
                $v[1] = '不支持';
                $v[2] = 'no';
                $this->check_error = true;
            }
        }

        return $items;
    }

    public function put_env($data)
    {
        $envPath = local_path('') . DS . '.env';
        $contentArray = collect(file($envPath, FILE_IGNORE_NEW_LINES));
        $contentArray->transform(function ($item) use ($data) {
            foreach ($data as $key => $value) {
                if (str_contains($item, $key)) {
                    return $key . '=' . $value;
                }
            }

            return $item;
        });
        $content = implode("\n", $contentArray->toArray());
        file_put_contents($envPath, $content);
    }
}
