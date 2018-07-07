<?php
declare(strict_types=1);

namespace Webuntis\Tests\Repositories;

use PHPUnit\Framework\TestCase;
use Webuntis\Repositories\ClassRegEventsRepository;
use Webuntis\Models\ClassRegEvents;
use Webuntis\Handler\ExecutionHandler;
use Webuntis\Configuration\WebuntisConfiguration;

/**
 * ClassRegEventsRepositoryTest
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
final class ClassRegEventsRepositoryTest extends TestCase
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
        $repository = new ClassRegEventsRepository(ClassRegEvents::class);

        $models = $repository->findAll();
        $this->assertEquals(3, count($models));

        $this->assertArrayHasKey('student', $models[0]->serialize());

        $this->assertEquals(3, $repository->findAll(['id' => 'DESC'])[0]->getId());

        $models = $repository->findAll([], 1);
        $this->assertEquals(1, count($models));

        $models = $repository->findBy(['student:id' => 1]);

        $this->assertEquals(0, count($models));
    }
}
