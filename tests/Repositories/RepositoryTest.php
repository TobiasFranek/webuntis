<?php
declare(strict_types=1);

namespace Webuntis\Tests\Repositories;

use PHPUnit\Framework\TestCase;
use Webuntis\Repositories\Repository;
use Webuntis\Models\Classes;
use Webuntis\Models\SchoolYears;
use Webuntis\Models\Period;
use Webuntis\Models\Substitutions;
use Webuntis\Handler\ExecutionHandler;
use Webuntis\Exceptions\RepositoryException;
use Webuntis\Configuration\WebuntisConfiguration;

/**
 * RepositoryTest
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
final class RepositoryTest extends TestCase
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
        $repository = new Repository(Classes::class);
        $this->assertEquals(4, count($repository->findAll()));

        $this->assertEquals(4, $repository->findAll(['id' => 'DESC'])[0]->getId());
        $this->assertEquals(2, count($repository->findAll([], 2)));
    }

    public function testFindBy() : void 
    {
        $repository = new Repository(Classes::class);

        $this->assertEquals('valid', $repository->findBy(['name' => '%d%'])[0]->getName());
        $this->assertEquals('valid', $repository->findBy(['name' => 'vali%'])[0]->getName());
        $this->assertEquals('valid', $repository->findBy(['name' => '%alid'])[0]->getName());
        $this->assertEquals('valid', $repository->findBy(['name' => 'valid'])[0]->getName());

        $repository = new Repository(Schoolyears::class);

        $this->assertEquals(3, count($repository->findBy(['startDate' => '>2013-01-01', 'endDate' => '<2016-12-31'])));

        WebuntisConfiguration::setConfig([ 
            'default' => [
                   //f.e. thalia, cissa etc.
                    'server' => 'yourserver',
                    'school' => 'yourschool',
                    'username' => 'yourusername',
                    'password' => 'yourpassword',
                    'ignore_children' => true
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
        $repository = new Repository(Substitutions::class);

        $this->assertEquals(2, count($repository->findAll()));

        $repository = new Repository(Period::class);

        $this->assertInternalType('array', $repository->findAll()[0]->getClasses());
        $this->assertInternalType('array', $repository->findAll()[0]->getTeachers());
        $this->assertInternalType('array', $repository->findAll()[0]->getSubjects());
        $this->assertInternalType('array', $repository->findAll()[0]->getRooms());

        $this->expectException(RepositoryException::class);
        $this->assertInternalType('array', $repository->findBy(['classes.name' => 'test']));

    }
}
