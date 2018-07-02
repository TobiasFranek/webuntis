<?php
declare(strict_types=1);

namespace Webuntis\Tests\Configuration;

use PHPUnit\Framework\TestCase;
use Webuntis\Configuration\WebuntisConfiguration;
use Webuntis\Repositories\Repository;

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
            'disableCache' => 'true'
        ]);

        $this->assertEquals(true, Repository::$disabledCache);
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
