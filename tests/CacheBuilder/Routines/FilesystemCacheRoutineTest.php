<?php
declare(strict_types=1);

namespace Webuntis\Tests\Configuration;

use PHPUnit\Framework\TestCase;
use Webuntis\Configuration\WebuntisConfiguration;
use Webuntis\CacheBuilder\Routines\FilesystemCacheRoutine;
use Doctrine\Common\Cache\FilesystemCache;

/**
 * FilesystemCacheRoutineTest
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
final class FilesystemCacheRoutineTest extends TestCase
{
    public function testExecute() : void
    {
        $filesystem = new FilesystemCacheRoutine();

        $cache = $filesystem->execute([
            'path' => 'tests/var'
        ]);
        $this->assertInstanceOf(FilesystemCache::class, $cache);
    }
}
