<?php
declare(strict_types=1);

namespace Webuntis\Tests\Configuration;

use PHPUnit\Framework\TestCase;
use Webuntis\Configuration\WebuntisConfiguration;
use Webuntis\CacheBuilder\Routines\ArrayCacheRoutine;
use Doctrine\Common\Cache\ArrayCache;

/**
 * ArrayCacheRoutineTest
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
final class ArrayCacheRoutineTest extends TestCase
{
    public function testExecute() : void
    {
        $arrayCache = new ArrayCacheRoutine();

        $cache = $arrayCache->execute([]);
        $this->assertInstanceOf(ArrayCache::class, $cache);
    }
}
