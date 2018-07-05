<?php
declare(strict_types=1);

namespace Webuntis\Tests\Repositories;

use PHPUnit\Framework\TestCase;
use Webuntis\Repositories\PeriodRepository;
use Webuntis\Models\Period;
use Webuntis\Handler\ExecutionHandler;
use Webuntis\Configuration\WebuntisConfiguration;

/**
 * PeriodRepositoryTest
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
final class PeriodRepositoryTest extends TestCase
{
    public function setUp() : void 
    {
        $config = new WebuntisConfiguration([ 
            'default' => [
                   //f.e. thalia, cissa etc.
                    'server' => 'yourserver',
                    'school' => 'yourschool',
                    'username' => 'yourusername',
                    'password' => 'yourpassword'
                ],
            'admin' => [
                   //f.e. thalia, cissa etc.
                    'server' => 'yourserver',
                    'school' => 'yourschool',
                    'username' => 'youradminusername',
                    'password' => 'youradminpassword'
            ],
            'security_manager' => 'Webuntis\Tests\Util\TestSecurityManager'
        ]);
    }
    public function testFindAll() : void
    {   
        $repository = new PeriodRepository(Period::class);

        $models = $repository->findAll();
        $this->assertEquals(3, count($models));

        $this->assertArrayHasKey('teachers', $models[0]->serialize());
        $this->assertArrayHasKey('classes', $models[0]->serialize());
        $this->assertArrayHasKey('subjects', $models[0]->serialize());
        $this->assertArrayHasKey('rooms', $models[0]->serialize());

    }

    public function testFindBy() : void
    {   
        $repository = new PeriodRepository(Period::class);

        $models = $repository->findBy(['id' => 1]);
        $this->assertEquals(1, count($models));

        $this->assertEquals(1, $models[0]->getId());
        $this->assertArrayHasKey('teachers', $models[0]->serialize());
        $this->assertArrayHasKey('classes', $models[0]->serialize());
        $this->assertArrayHasKey('subjects', $models[0]->serialize());
        $this->assertArrayHasKey('rooms', $models[0]->serialize());

    }
}
