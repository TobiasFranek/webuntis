<?php
declare(strict_types=1);

namespace Webuntis\Tests\Configuration;

use PHPUnit\Framework\TestCase;
use Webuntis\Configuration\WebuntisConfiguration;
use Webuntis\CacheBuilder\Routines\MemcacheRoutine;

/**
 * MemcacheRoutineTest
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
final class MemcacheRoutineTest extends TestCase
{
    public function testExecute() : void
    {
        $memcache = new MemcacheRoutine();

        $cache = $memcache->execute([
            'host' => 'localhost',
            'port' => 11211
        ]);

        if(extension_loaded('memcached')) {
            $this->assertInstanceOf(\Webuntis\CacheBuilder\Cache\Memcached::class, $cache);
        } else {
            $this->assertNull($cache);
        }
    }
}
