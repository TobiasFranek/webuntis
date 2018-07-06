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
}