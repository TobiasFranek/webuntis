<?php
declare(strict_types=1);

namespace Webuntis\Tests\Configuration;

use PHPUnit\Framework\TestCase;
use Webuntis\Configuration\WebuntisConfiguration;
use Webuntis\Repositories\Repository;
use Webuntis\CacheBuilder\CacheBuilder;

/**
 * WebuntisConfigurationTest
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
final class WebuntisConfigurationTest extends TestCase
{
    public function testSetConfig() : void
    {
        WebuntisConfiguration::setConfig([
            'test' => 'test'
        ]);

        $this->assertEquals(['test' => 'test'], WebuntisConfiguration::getConfig());

        WebuntisConfiguration::setConfig([
            'test' => 'test',
            'disable_cache' => 'true'
        ]);

        $cacheBuilder = new CacheBuilder();

        $this->assertEquals(true, $cacheBuilder->isCacheDisabled());
    }

    public function testAddConfig() : void
    {
        WebuntisConfiguration::setConfig([
            'test' => 'test'
        ]);

        $this->assertEquals(['test' => 'test'], WebuntisConfiguration::getConfig());
        
        WebuntisConfiguration::addConfig([
            'test1' => 'test1' 
        ]);

        $this->assertEquals(['test' => 'test', 'test1' => 'test1'], WebuntisConfiguration::getConfig());

    }
}
