<?php
declare(strict_types=1);

namespace Webuntis\CacheBuilder\Routines\Interfaces;

/**
 * Interface for the CacheBuilderRoutine
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
interface CacheBuilderRoutineInterface {
    
    /**
     * executes the CacheBuilderRoutine so a Caching instance can be created
     * @param array $config
     * @return object|bool
     */
    public static function execute(array $config);

    /**
     * return how the config should be given
     * is used by the commands
     * @return array
     */
    public static function getConfigMeta() : array;

    /**
     * return the name of the routine
     * @return string
     */
    public static function getName() : string;
}