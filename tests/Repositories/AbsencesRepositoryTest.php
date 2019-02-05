<?php
declare(strict_types=1);

namespace Webuntis\Tests\Repositories;

use PHPUnit\Framework\TestCase;
use Webuntis\Repositories\AbsencesRepository;
use Webuntis\Models\Absences;
use Webuntis\Handler\ExecutionHandler;
use Webuntis\Configuration\WebuntisConfiguration;

/**
 * AbsencesRepositoryTest
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
final class AbsencesRepositoryTest extends TestCase
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
        $repository = new AbsencesRepository(Absences::class);

        $models = $repository->findAll();
        $this->assertEquals(2, count($models));

        $this->assertArrayHasKey('student', $models[0]->serialize());
        $this->assertArrayHasKey('subject', $models[0]->serialize());
        $this->assertArrayHasKey('teachers', $models[0]->serialize());
        
        $this->assertEquals(2, $repository->findAll(['id' => 'DESC'])[0]->getId());

        $models = $repository->findAll([], 1);
        $this->assertEquals(1, count($models));
    }

    public function testFindBy() : void
    {   
        $repository = new AbsencesRepository(Absences::class);

        $models = $repository->findBy(['id' => 1]);
        $this->assertEquals(1, count($models));

        $this->assertEquals(1, $models[0]->getId());
        $this->assertArrayHasKey('student', $models[0]->serialize());
        $this->assertArrayHasKey('subject', $models[0]->serialize());
        $this->assertArrayHasKey('teachers', $models[0]->serialize());

        $this->assertEquals(1, $repository->findBy(['id' => 1], ['id' => 'DESC'])[0]->getId());

        $models = $repository->findAll([], 1);
        $this->assertEquals(1, count($models));
    }
}
