<?php
declare(strict_types=1);

namespace Webuntis\Tests\Configuration;

use PHPUnit\Framework\TestCase;
use Webuntis\Configuration\WebuntisConfiguration;
use Webuntis\CacheBuilder\CacheBuilder;

/**
 * CacheBuilderTest
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
final class CacheBuilderTest extends TestCase
{
    public function testSetConfig() : void
    {
        WebuntisConfiguration::setConfig([
            'cache' => [
                'host' => 'test'
            ]
        ]);

        $cacheBuilder = new CacheBuilder();

        $this->assertEquals([
            'host' => 'test'
        ], $cacheBuilder->getConfig());

        $cacheBuilder = new CacheBuilder([
            'cache' => [
                'host' => 'test'
            ]
        ]);

        $this->assertEquals([
            'host' => 'test'
        ], $cacheBuilder->getConfig());

        $cacheBuilder = new CacheBuilder([
            'disable_cache' => true
        ]);

        $this->assertTrue($cacheBuilder->isCacheDisabled());

        $cacheBuilder = new CacheBuilder([
            'cache' => [
                'routines' => [
                    'test' => 'TestRoutine'
                ]
            ]
        ]);

        $this->assertEquals([
            'test' => 'TestRoutine',
            'memcached' => 'Webuntis\CacheBuilder\Routines\MemcacheRoutine'
        ], $cacheBuilder->getRoutines());
    }

    public function testCreate() {
        $cacheBuilder = new CacheBuilder([
            'cache' => [
                'type' => 'memcached',
                'routines' => [
                    'test' => 'TestRoutine'
                ]
            ]
        ]);

        $cache = $cacheBuilder->create();
        if(extension_loaded('memcached')) {
            $this->assertInstanceOf(\Webuntis\CacheBuilder\Cache\Memcached::class, $cache);
        } else {
            $this->assertNull($cache);
        }
    }
}
