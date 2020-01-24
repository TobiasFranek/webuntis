<?php
declare(strict_types=1);

namespace Webuntis\Tests\Repositories;

use PHPUnit\Framework\TestCase;
use Webuntis\Repositories\ClassHasTeachersRepository;
use Webuntis\Configuration\WebuntisConfiguration;
use Webuntis\Models\Classes;
use Webuntis\Models\SchoolYears;
use Webuntis\Models\Period;
use Webuntis\Models\Teachers;
use Webuntis\Models\ClassHasTeachers;
use Webuntis\Models\Rooms;
use Webuntis\Models\Subjects;
use Webuntis\Handler\ExecutionHandler;

/**
 * ClassHasTeachersRepositoryTest
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
final class ClassHasTeachersRepositoryTest extends TestCase
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
        $repository = new ClassHasTeachersRepository(ClassHasTeachers::class);
        $data = $repository->getInstance()->getClient()->getData();
        $models = $repository->findAll();
        $this->assertEquals(4, count($models));
        $this->assertEquals('test', $models[0]->getName());
        $this->assertEquals('sie', $models[1]->getName());
        $this->assertEquals('valid', $models[2]->getName());
        $this->assertEquals('thisisastring', $models[3]->getName());

        $this->assertArrayHasKey('teachers', $models[0]->serialize());
        $this->assertArrayHasKey('teachers', $models[1]->serialize());
        $this->assertArrayHasKey('teachers', $models[2]->serialize());
        $this->assertArrayHasKey('teachers', $models[3]->serialize());

        $this->assertEquals(4, $repository->findAll(['id' => 'DESC'])[0]->getId());

        $models = $repository->findAll([], 1);
        $this->assertEquals(1, count($models));
    }
}
