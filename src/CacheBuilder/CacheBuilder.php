<?php
declare(strict_types=1);

namespace Webuntis\CacheBuilder;

use Webuntis\CacheBuilder\Routines\MemcacheRoutine;
use Webuntis\CacheBuilder\Routines\FilesystemCacheRoutine;
use Webuntis\CacheBuilder\Routines\ArrayCacheRoutine;
use Webuntis\Configuration\WebuntisConfiguration;
use Webuntis\CacheBuilder\Cache\Memcached;

/**
 * The CacheBuilder creates different Caching Instances from the given config
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class CacheBuilder {

    /**
     * @var object[]
     */
    private static $caches = [];

    /**
     * @var string
     */
    const DEFAULT = 'memcached';

    /**
     * @var array
     */
    private $routines = [
        'memcached' => MemcacheRoutine::class,
        'filesystem' => FilesystemCacheRoutine::class,
        'arraycache' => ArrayCacheRoutine::class
    ]; 

    /**
     * @var string
     */
    private $cacheType;

    /**
     * @var array
     */
    private $config = [];

    /**
     * @var boolean
     */
    private $cacheDisabled = false;

    public function __construct(array $passedConfig = []) 
    {
        if (empty($passedConfig)) {
            $config = WebuntisConfiguration::getConfig();
        } else {
            $config = $passedConfig;
        }
        $this->cacheType = self::DEFAULT;

        if (isset($config['disable_cache'])) {
            $this->cacheDisabled = boolval($config['disable_cache']);
        }
        if (isset($config['cache'])) {
            if (isset($config['cache']['routines'])) {
                $this->routines = array_merge($config['cache']['routines'], $this->routines);
            }
            if (isset($config['cache']['type'])) {
                $this->cacheType = $config['cache']['type'];
            }
            $this->config = $config['cache'];
        }

    }

    /**
     * creates an Cache Instance
     * @return object
     */
    public function create() : ?object
    {
        if (!$this->cacheDisabled) {
            if (!isset(self::$caches[$this->cacheType])) {
                self::$caches[$this->cacheType] = $this->routines[$this->cacheType]::execute($this->config);
            } 
            return self::$caches[$this->cacheType];
        } else {
            return null;
        }
    }

    /**
     * returns if the cache is disabled 
     * @return bool
     */
    public function isCacheDisabled() : bool
    {
        return $this->cacheDisabled;
    }

    /**
     * return the initialized caching routines
     * @return array
     */
    public function getRoutines() : array
    {
        return $this->routines;
    }

    /**
     * return the config array of the cache builder
     * @return array
     */
    public function getConfig() : array 
    {
        return $this->config;
    }
}