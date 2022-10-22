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
namespace Bra\core;

use Bra\core\cache\BraCache;
use Dotenv\Dotenv;
use Illuminate\Support\Arr;

class Config {

    public array $items = [];
    public string $config_path;
    public string $cache_config_path;

    public function __construct() {
        $this->cache_config_path = local_path('storage') . "config.php";
        if (!$this->get('app.debug') && file_exists($this->cache_config_path)) {
            $this->items = require $this->cache_config_path;
        } else {
            $this->load_config();
        }
    }

    public function load_config(): void {
        $dotenv = Dotenv::createImmutable(BRA_PATH);
        $dotenv->load();
        $this->config_path = BRA_PATH . DS . 'config';
        $this->load();
        if (!$this->get('app.debug') && !file_exists($this->cache_config_path)) {
            file_put_contents($this->cache_config_path, '<?php return ' . var_export($this->get(null), true) . ';' . PHP_EOL);
        }
    }

    public function set($key, $value = null) {
        $configs = is_array($key) ? $key : [$key => $value];
        foreach ($configs as $key => $value) {
            Arr::set($this->items, $key, $value);
        }
    }

    public function get($key, $default = null) {
        if (is_array($key)) {
            return $this->getMany($key);
        }
        return Arr::get($this->items, $key, $default);
    }

    /**
     * Get many configuration values.
     *
     * @param array $keys
     * @return array
     */
    public function getMany(array $keys): array {
        $config = [];
        foreach ($keys as $key => $default) {
            if (is_numeric($key)) {
                [$key, $default] = [$default, null];
            }
            $config[$key] = Arr::get($this->items, $key, $default);
        }
        return $config;
    }

    function load(): void {
        foreach (glob($this->config_path . "/*.php") as $filename) {
            $path_parts = pathinfo($filename);
            $this->set($path_parts['filename'], require $filename);
        }
        foreach (glob(local_path('bra') . "*") as $filename) {
            $path_parts = pathinfo($filename);
            $module = $path_parts['basename'];
            $filename = local_path('bra' . DS . $module) . $module . "_config.php";
            $install_lock = local_path('bra' . DS . $module) . $module . "_install.lock";
            if (is_file($filename) && is_file($install_lock)) {
                $this->load_module_config(require $filename);
            }
        }
    }

    public function load_module_config($module_config): void {
        #middlewares
        if (is_array($module_config['middle_middlewares'] ?? false)) {
            $this->items['app']['middle_middlewares'] = array_merge($this->items['app']['middle_middlewares'], $module_config['middle_middlewares']);
        }
        if (is_array($module_config['after_middlewares'] ?? false)) {
            $this->items['app']['after_middlewares'] = array_merge($this->items['app']['after_middlewares'], $module_config['after_middlewares']);
        }
        #routes
        if (is_array($module_config['routes'] ?? false)) {
            $this->items['routes'] = array_merge_recursive($this->items['routes'], $module_config['routes']);
        }
        #files
        if (is_array($module_config['extra_files'] ?? false)) {
            $this->items['app']['extra_files'] = array_merge($this->items['app']['extra_files'], $module_config['extra_files']);
        }
        #aliases
        if (is_array($module_config['aliases'] ?? false)) {
            $this->items['app']['aliases'] = array_merge($this->items['app']['aliases'], $module_config['aliases']);
        }
        #events
        if (is_array($module_config['event_observers'] ?? false)) {
            $this->items['app']['event_observers'] = array_merge($this->items['app']['event_observers'], $module_config['event_observers']);
        }
        #cors
        if (is_array($module_config['cors']['paths'] ?? false)) {
            $this->items['cors']['paths'] = array_merge($this->items['cors']['paths'], $module_config['cors']['paths']);
        }
    }
}
