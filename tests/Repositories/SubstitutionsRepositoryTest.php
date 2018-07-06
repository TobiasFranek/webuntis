<?php
declare(strict_types=1);

namespace Webuntis\Tests\Repositories;

use PHPUnit\Framework\TestCase;
use Webuntis\Repositories\SubstitutionsRepository;
use Webuntis\Models\Substitutions;
use Webuntis\Handler\ExecutionHandler;
use Webuntis\Configuration\WebuntisConfiguration;

/**
 * SubstitutionsRepositoryTest
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
final class SubstitutionsRepositoryTest extends TestCase
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
    public function testfindAll() : void
    {   
        $repository = new SubstitutionsRepository(Substitutions::class);

        $models = $repository->findAll([], null, 0, '2018-07-04', '2018-08-04');
        
        $this->assertEquals(2, count($models));

        $this->assertArrayHasKey('teachers', $models[0]->serialize());
        $this->assertArrayHasKey('classes', $models[0]->serialize());
        $this->assertArrayHasKey('rooms', $models[0]->serialize());
        $this->assertArrayHasKey('subjects', $models[0]->serialize());

        $this->assertEquals(2, $repository->findAll(['id' => 'DESC'], null , 0, '2018-07-04', '2018-08-04')[0]->getId());

        $models = $repository->findAll([], 1, 0, '2018-07-04', '2018-08-04');
        $this->assertEquals(1, count($models));
    }

    public function testFindBy() : void
    {   
        $repository = new SubstitutionsRepository(Substitutions::class);

        $models = $repository->findBy(['id' => 1], [], null, 0, '2018-07-04', '2018-08-04');
        $this->assertEquals(1, count($models));

        $this->assertEquals(1, $models[0]->getId());
        $this->assertArrayHasKey('teachers', $models[0]->serialize());
        $this->assertArrayHasKey('classes', $models[0]->serialize());
        $this->assertArrayHasKey('subjects', $models[0]->serialize());
        $this->assertArrayHasKey('rooms', $models[0]->serialize());

        $this->assertEquals(1, $repository->findBy(['id' => 1], ['id' => 'DESC'], null , 0, '2018-07-04', '2018-08-04')[0]->getId());

        $models = $repository->findAll([], 1, 0, '2018-07-04', '2018-08-04');
        $this->assertEquals(1, count($models));
    }
}
