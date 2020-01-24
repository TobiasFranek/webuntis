<?php
declare(strict_types=1);

namespace Webuntis\Tests\Configuration;

use PHPUnit\Framework\TestCase;
use Webuntis\Configuration\YAMLConfiguration;

/**
 * YamlConfigurationTest
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
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
            'Schoolyears' => 'Webuntis\Models\Schoolyears',
            'Students' => 'Webuntis\Models\Students',
            'Subjects' => 'Webuntis\Models\Subjects',
            'Substitutions' => 'Webuntis\Models\Substitutions',
            'Teachers' => 'Webuntis\Models\Teachers',
            'ClassRegEvents' => 'Webuntis\Models\ClassRegEvents',
            'StatusData' => 'Webuntis\Models\StatusData',
            'TimegridUnits' => 'Webuntis\Models\TimegridUnits',
            'LastImportTime' => 'Webuntis\Models\LastImportTime',
            'Absences' => 'Webuntis\Models\Absences',
            'RemarkCategories' => 'Webuntis\Models\RemarkCategories',
            'RemarkCategoryGroups' => 'Webuntis\Models\RemarkCategoryGroups'
        ];
        $expectedRepositories = [
            'Default' => 'Webuntis\Repositories\Repository',
            'User' => 'Webuntis\Repositories\UserRepository',
            'ClassHasTeachers' => 'Webuntis\Repositories\ClassHasTeachersRepository',
            'Exams' => 'Webuntis\Repositories\ExamsRepository',
            'Period' => 'Webuntis\Repositories\PeriodRepository',
            'Schoolyears' => 'Webuntis\Repositories\SchoolyearsRepository',
            'Substitutions' => 'Webuntis\Repositories\SubstitutionsRepository',
            'ClassRegEvents' => 'Webuntis\Repositories\ClassRegEventsRepository',
            'StatusData' => 'Webuntis\Repositories\StatusDataRepository',
            'LastImportTime' => 'Webuntis\Repositories\LastImportTimeRepository',
            'Absences' => 'Webuntis\Repositories\AbsencesRepository'
        ];
        $gottenModels = $yamlConfiguration->getModels();

        $gottenRepositories = $yamlConfiguration->getRepositories();

        $this->assertEquals($expectedModels, $gottenModels);
        $this->assertEquals($expectedRepositories, $gottenRepositories);
    }
}
