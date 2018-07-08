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
class MemcacheRoutine implements CacheBuilderRoutineInterface {

    /**
     * the logic which is used to create an Memcache instance
     * @var array $config
     * @return bool|Memcached
     */
    public static function execute(array $config) 
    {
        if (extension_loaded('memcached')) {
            $cacheDriver = new Memcached();
            $host = 'localhost';
            $port = 11211;
            if (!empty($config)) {
                if (isset($config['host'])) {
                    $host = $config['host'];
                }
                if (isset($config['port'])) {
                    $port = $config['port'];
                }
            }
            $memcached = new \Memcached();
            $memcached->addServer($host, $port);
            $cacheDriver->setMemcached($memcached);
            return $cacheDriver;
        } else {
            return false;
        }
    }

    /**
     * return how the config should be given
     * is used by the commands
     * @return array
     */
    public static function getConfigMeta() : array
    {
        return [
            [
                'name' => 'host',
                'default' => 'localhost',
                'question' => 'Host [localhost]: '
            ],
            [
                'name' => 'port',
                'default' => 11211,
                'question' => 'Port [11211]: '
            ]
        ];
    }

    /**
     * return the name of the routine
     * @return string
     */
    public static function getName() : string 
    {
        return 'memcached';
    }
}