<?php
declare(strict_types=1);

namespace Webuntis\CacheBuilder\Routines;

use Webuntis\CacheBuilder\Routines\Interfaces\CacheBuilderRoutineInterface;
use Doctrine\Common\Cache\ArrayCache;


/**
 * The ArrayCacheRoutine which creates an memcached instance
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class ArrayCacheRoutine implements CacheBuilderRoutineInterface {

    /**
     * the logic which is used to create an ArrayCache instance
     * @var array $config
     * @return object
     */
    public static function execute(array $config) : ?object
    {
        return new ArrayCache();
    }

    /**
     * return how the config should be given
     * is used by the commands
     * @return array
     */
    public static function getConfigMeta() : array
    {
        return [];
    }

    /**
     * return the name of the routine
     * @return string
     */
    public static function getName() : string 
    {
        return 'arraycache';
    }
}