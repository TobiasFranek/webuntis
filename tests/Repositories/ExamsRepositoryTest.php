<?php
declare(strict_types=1);

namespace Webuntis\Tests\Repositories;

use PHPUnit\Framework\TestCase;
use Webuntis\Repositories\ExamsRepository;
use Webuntis\Models\Exams;
use Webuntis\Handler\ExecutionHandler;
use Webuntis\Configuration\WebuntisConfiguration;

/**
 * ExamsRepositoryTest
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
final class ExamsRepositoryTest extends TestCase
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
        $repository = new ExamsRepository(Exams::class);

        $models = $repository->findAll();
        $this->assertEquals(2, count($models));

        $this->assertArrayHasKey('teachers', $models[0]->serialize());
        $this->assertArrayHasKey('classes', $models[0]->serialize());
        $this->assertArrayHasKey('students', $models[0]->serialize());
        $this->assertArrayHasKey('subject', $models[0]->serialize());

        $this->assertEquals(2, $repository->findAll(['id' => 'DESC'])[0]->getId());

        $models = $repository->findAll([], 1);
        $this->assertEquals(1, count($models));

        $models = $repository->findBy(['subject:id' => 1]);

        $this->assertEquals(2, count($models));
    }
}
