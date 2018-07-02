<?php
declare(strict_types=1);

namespace Webuntis\Tests\Configuration;

use PHPUnit\Framework\TestCase;
use Webuntis\Configuration\YAMLConfiguration;

final class YamlConfigurationTest extends TestCase
{
    public function testLoad() : void
    {
        $yamlConfiguration = new YamlConfiguration();
        $expectedModels = [
            'ClassHasTeachers' => 'Webuntis\Models\ClassHasTeachers',
            'Classes' => 'Webuntis\Models\Classes',
            'Departments' => 'Webuntis\Models\Departments',
            'ExamTypes' => 'Webuntis\Models\ExamTypes',
            'Exams' => 'Webuntis\Models\Exams',
            'Holidays' => 'Webuntis\Models\Holidays',
            'Period' => 'Webuntis\Models\Period',
            'Rooms' => 'Webuntis\Models\Rooms',
            'Schoolyear' => 'Webuntis\Models\Schoolyear',
            'Students' => 'Webuntis\Models\Students',
            'Subjects' => 'Webuntis\Models\Subjects',
            'Substitutions' => 'Webuntis\Models\Substitutions',
            'Teachers' => 'Webuntis\Models\Teachers',
        ];
        $expectedRepositories = [
            'Default' => 'Webuntis\Repositories\Repository',
            'User' => 'Webuntis\Repositories\UserRepository',
            'ClassHasTeachers' => 'Webuntis\Repositories\ClassHasTeachersRepository',
            'Exams' => 'Webuntis\Repositories\ExamsRepository',
            'Period' => 'Webuntis\Repositories\PeriodRepository',
            'Schoolyear' => 'Webuntis\Repositories\SchoolyearRepository',
            'Substitutions' => 'Webuntis\Repositories\SubstitutionsRepository'
        ];
        $gottenModels = $yamlConfiguration->getModels();

        $gottenRepositories = $yamlConfiguration->getRepositories();

        $this->assertEquals($expectedModels, $gottenModels);
        $this->assertEquals($expectedRepositories, $gottenRepositories);
    }
}
