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
namespace Bra\core\cache;

use Bra\core\Holder;
use Predis\ClientInterface;
use Redis;
use RedisArray;
use RedisCluster;
use Symfony\Component\Cache\Adapter\AbstractTagAwareAdapter;
use Symfony\Component\Cache\Adapter\FilesystemTagAwareAdapter;
use Symfony\Component\Cache\Adapter\RedisTagAwareAdapter;
use Symfony\Component\Cache\Marshaller\DefaultMarshaller;
use Symfony\Component\Cache\Marshaller\DeflateMarshaller;
use Symfony\Component\Cache\Traits\RedisClusterProxy;
use Symfony\Component\Cache\Traits\RedisProxy;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\Store\RedisStore;

class BraCache extends Holder {

    public static AbstractTagAwareAdapter $holder;
    public static RedisArray|Redis|RedisProxy|RedisClusterProxy|RedisCluster|ClientInterface $lock_store;
    public static string $cache_driver;

    public function __construct () {
        self::$cache_driver = $cache_driver = config('cache.default');
        $this->$cache_driver();
    }

    public static function clear() {
        self::$holder->clear();
        $cache_config_path = local_path('storage') . "config.php";
        if ( file_exists($cache_config_path)) {
            unlink( $cache_config_path);
        }
    }

    public static function get ($cache_key, callable $closure = null) {
        $prefix = config('redis.cache_prefix');
        $cache_key = $prefix . $cache_key;
        $item = self::$holder->getItem($cache_key);
        if (!$item->isHit()) {
            if (is_callable($closure)) {
                return $closure($item);
            } else {
                return null;
            }
        } else {
            $res = $item->get();

            return $res;
        }
    }

    public static function lock ($cache_key, callable $closure) {
        $prefix = config('redis.cache_prefix');
        $cache_key = $prefix . $cache_key;
        $store = new RedisStore(self::$lock_store);
        $factory = new LockFactory($store);
        $lock = $factory->createLock($cache_key . "_cache_lock", 10);
        $lock->acquire();

        return $closure($lock);
    }

    public static function set ($cache_key, $value, $tags = [], $expire = 3600) {
        $prefix = config('redis.cache_prefix');
        $cache_key = $prefix . $cache_key;
        $item = self::$holder->getItem($cache_key);
        $item->expiresAfter($expire);
        if ($tags) {
            foreach ($tags as &$tag){
                $tag = $prefix . $tag;
            }
            $item->tag($tags);
        }
        $item->set($value);
        self::$holder->save($item);
    }

    public static function del ($cache_key) {
        $prefix = config('redis.cache_prefix');
        $cache_key = $prefix . $cache_key;
        self::$holder->delete($cache_key);
    }

    public static function del_tags ($tags) {
        $prefix = config('redis.cache_prefix');
        foreach ($tags as &$tag){
            $tag = $prefix . $tag;
        }
        self::$holder->invalidateTags($tags);
    }

    public function redis () {
        $redises = config('cache.stores')["redis"];
        $connection = $redises['connection'];
        $lock_connection = $redises['lock_connection'];
        $marshaller = new DeflateMarshaller(new DefaultMarshaller());
        $redis = RedisTagAwareAdapter::createConnection(config("redis")[$connection]);
        self::$holder = new RedisTagAwareAdapter($redis, '', $redises['expire'] ?? 8640000, $marshaller);
        self::$lock_store = RedisTagAwareAdapter::createConnection(config("redis")[$lock_connection]);
    }

    public function file () {
        self::$holder = new FilesystemTagAwareAdapter();
    }
}
