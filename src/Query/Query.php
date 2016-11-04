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

use Webuntis\Exceptions\QueryException;
use Webuntis\Models\Departments;
use Webuntis\Models\Holidays;
use Webuntis\Models\Period;
use Webuntis\Models\Rooms;
use Webuntis\Models\Subjects;
use Webuntis\Models\Students;
use Webuntis\Models\Teachers;
use Webuntis\Models\Classes;
use Webuntis\Repositories\PeriodRepository;
use Webuntis\Repositories\Repository;
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
    private static $chachedRepositories = [];

    /**
     * @var array
     */
    private $repositories = [
        'Default' => Repository::class,
        'Period' => PeriodRepository::class,
        'Students' => StudentsRepository::class,
        'User' => UserRepository::class
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
    ];

    /**
     * Query constructor.
     * @param array $models
     * @param array $repositories
     */
    public function __construct(array $models = [], array $repositories = []) {
        $this->models = array_merge($this->models, $models);
        $this->repositories = array_merge($this->repositories, $repositories);
    }

    /**
     * gets the right repository to the right model
     * @param string $className
     * @return Repository
     */
    public function get($className) {
        if($className == 'User') {
            if (!isset(static::$chachedRepositories[$className])) {
                static::$chachedRepositories[$className] = new $this->repositories[$className]();
            }
            return static::$chachedRepositories[$className];
        }
        if (isset($this->models[$className])) {
            if (isset($this->repositories[$className])) {
                $name = $className;
            } else {
                $name = 'Default';
            }
            if (!isset(static::$chachedRepositories[$className])) {
                static::$chachedRepositories[$className] = new $this->repositories[$name]($this->models[$className]);
            }
            return static::$chachedRepositories[$className];
        }
        throw new QueryException('Model ' . $className . ' not found');
    }
}