<?php
declare(strict_types=1);

namespace Webuntis\CacheBuilder\Routines;

use Webuntis\CacheBuilder\Routines\Interfaces\CacheBuilderRoutineInterface;
use Webuntis\CacheBuilder\Cache\Memcached;

/**
 * The MemcacheRoutine which creates an memcached instance
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class MemcacheRoutine implements CacheBuilderRoutineInterface  {

    /**
     * the logic which is used to create an Memcache instance
     * @var array $config
     * @return bool|object
     */
    public static function execute(array $config) 
    {
        if (extension_loaded('memcached')) {
            $cacheDriver = new Memcached();
            $host = 'localhost';
            $port = 11211;
            if(!empty($config)) {
                if(isset($config['host'])) {
                    $host = $config['host'];
                }
                if(isset($config['port'])) {
                    $port = $config['port'];
                }
            }
            $memcached = new \Memcached();
            $memcached->addServer($host, $port);
            $cacheDriver->setMemcached($memcached);
        } else {
            return false;
        }
    }
}