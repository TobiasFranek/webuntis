<?php
declare(strict_types=1);

namespace Webuntis\Tests\Query;

use PHPUnit\Framework\TestCase;
use Webuntis\Query\Query;
use Webuntis\Repositories\Repository;
use Webuntis\Models\Classes;
use Webuntis\Repositories\UserRepository;
use Webuntis\Repositories\ClassHasTeachersRepository;
use Webuntis\Models\ClassHasTeachers;
use Webuntis\Repositories\ExamsRepository;
use Webuntis\Models\Exams;
use Webuntis\Repositories\PeriodRepository;
use Webuntis\Models\Period;
use Webuntis\Repositories\SchoolyearsRepository;
use Webuntis\Exceptions\QueryException;
use Webuntis\Models\Schoolyears;
use Webuntis\Repositories\SubstitutionsRepository;
use Webuntis\Models\Substitutions;
use Webuntis\Configuration\WebuntisConfiguration;

final class QueryTest extends TestCase
{
    public function testGet() : void
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
            'security_manager' => 'Webuntis\Tests\TestSecurityManager'
        ]);
        $query = new Query();
        
        $this->assertInstanceOf(Repository::class, $query->get('Classes'));
        $this->assertEquals(Classes::class, $query->get('Classes')->getModel());
        $this->assertInstanceOf(UserRepository::class, $query->get('User'));
        $this->assertInstanceOf(ClassHasTeachersRepository::class, $query->get('ClassHasTeachers'));
        $this->assertEquals(ClassHasTeachers::class, $query->get('ClassHasTeachers')->getModel());
        $this->assertInstanceOf(ExamsRepository::class, $query->get('Exams'));
        $this->assertEquals(Exams::class, $query->get('Exams')->getModel());
        $this->assertInstanceOf(PeriodRepository::class, $query->get('Period'));
        $this->assertEquals(Period::class, $query->get('Period')->getModel());
        $this->assertInstanceOf(SchoolyearsRepository::class, $query->get('Schoolyears'));
        $this->assertEquals(Schoolyears::class, $query->get('Schoolyears')->getModel());
        $this->assertInstanceOf(SubstitutionsRepository::class, $query->get('Substitutions'));
        $this->assertEquals(Substitutions::class, $query->get('Substitutions')->getModel());

        $this->expectException(QueryException::class);
        $query->get('notvalid');
    }
}
