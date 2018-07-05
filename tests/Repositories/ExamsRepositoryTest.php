<?php
declare(strict_types=1);

namespace Webuntis\Tests\Repositories;

use PHPUnit\Framework\TestCase;
use Webuntis\Repositories\ExamsRepository;
use Webuntis\Models\Exams;
use Webuntis\Handler\ExecutionHandler;
use Webuntis\Configuration\WebuntisConfiguration;

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

    }
}
