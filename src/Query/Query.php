<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

namespace Webuntis\Query;

use Webuntis\Configuration\QueryConfiguration;
use Webuntis\Exceptions\QueryException;
use Webuntis\Models\ClassHasTeachers;
use Webuntis\Models\Departments;
use Webuntis\Models\Exams;
use Webuntis\Models\ExamTypes;
use Webuntis\Models\Holidays;
use Webuntis\Models\Period;
use Webuntis\Models\Rooms;
use Webuntis\Models\Schoolyear;
use Webuntis\Models\Subjects;
use Webuntis\Models\Students;
use Webuntis\Models\Substitutions;
use Webuntis\Models\Teachers;
use Webuntis\Models\Classes;
use Webuntis\Repositories\ClassHasTeachersRepository;
use Webuntis\Repositories\ExamsRepository;
use Webuntis\Repositories\PeriodRepository;
use Webuntis\Repositories\Repository;
use Webuntis\Repositories\SchoolyearRepository;
use Webuntis\Repositories\StudentsRepository;
use Webuntis\Repositories\UserRepository;

/**
 * Class Query
 * @package Webuntis\Query
 * @author Tobias Franek <tobias.franek@gmail.com>
 */
class Query {

    /**
     * @var Repository[]
     */
    private static $cachedRepositories = [];

    /**
     * @var array
     */
    private $repositories = [
        'Default' => Repository::class,
        'Period' => PeriodRepository::class,
        'User' => UserRepository::class,
        'ClassHasTeachers' => ClassHasTeachersRepository::class,
        'Schoolyear' => SchoolyearRepository::class,
        'Exams' => ExamsRepository::class,
        'Substitutions' => Substitutions::class
    ];

    /**
     * @var array
     */
    private $models = [
        'Period' => Period::class,
        'Students' => Students::class,
        'Teachers' => Teachers::class,
        'Classes' => Classes::class,
        'Subjects' => Subjects::class,
        'Rooms' => Rooms::class,
        'Departments' => Departments::class,
        'Holidays' => Holidays::class,
        'ClassHasTeachers' => ClassHasTeachers::class,
        'Schoolyear' => Schoolyear::class,
        'Exams' => Exams::class,
        'ExamTypes' => ExamTypes::class,
        'Substitutions' => Substitutions::class
    ];

    /**
     * Query constructor.
     * @param array $models
     * @param array $repositories
     */
    public function __construct(array $models = [], array $repositories = []) {
        $this->models = array_merge($this->models, $models);
        $this->repositories = array_merge($this->repositories, $repositories);
        $config = new QueryConfiguration();

        $config->getModels();
        $config->getRepositories();

        print "test";
    }

    /**
     * gets the right repository to the right model
     * @param string $className
     * @return Repository
     */
    public function get($className) {
        if($className == 'User') {
            if (!isset(static::$cachedRepositories[$className])) {
                static::$cachedRepositories[$className] = new $this->repositories[$className]();
            }
            return static::$cachedRepositories[$className];
        }
        if (isset($this->models[$className])) {
            if (isset($this->repositories[$className])) {
                $name = $className;
            } else {
                $name = 'Default';
            }
            if (!isset(static::$cachedRepositories[$className])) {
                static::$cachedRepositories[$className] = new $this->repositories[$name]($this->models[$className]);
            }
            return static::$cachedRepositories[$className];
        }
        throw new QueryException('Model ' . $className . ' not found');
    }
}