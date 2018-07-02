<?php
declare(strict_types=1);

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

namespace Webuntis\Repositories;

use Webuntis\Models\Classes;
use Webuntis\Query\Query;
use Webuntis\Util\ExecutionHandler;

/**
 * Class ClassHasTeachersRepository
 * @package Webuntis\Repositories
 * @author Tobias Franek <tobias.franek@gmail.com>
 */
class ClassHasTeachersRepository extends Repository {

    /**
     * @param array $sort
     * @param int $limit
     * @return array
     */
    public function findAll(array $sort = [], ?int $limit = null) : array 
    {
        $cache = self::getCache();
        $classesHaveTeachers = [];
        if ($cache && $cache->contains('ClassesHaveTeachers')) {
            $classesHaveTeachers = $cache->fetch('ClassesHaveTeachers');
        } else {
            $query = new Query();

            $classes = ExecutionHandler::execute(new Repository(Classes::class), []);

            foreach ($classes as $class) {
                $class = $class->serialize();
                $class['teachers'] = [];
                $classesHaveTeachers[] = new $this->model($class);
            }
            $schoolyear = $query->get('Schoolyear')->findAll();
            foreach ($classesHaveTeachers as $key => $value) {
                $periods = $query->get('Period')->findAll([], null, $value->getId(), 1, date_format($schoolyear->getStartDate(), 'Ymd'), date_format($schoolyear->getEndDate(), 'Ymd'));
                $tempTeachers = [];
                foreach ($periods as $value2) {
                    $teachers = $value2->getTeachers();
                    foreach ($teachers as $value3) {
                        $tempTeachers[$value3->getId()] = $value3;
                    }
                }
                $classesHaveTeachers[$key]->setTeachers($tempTeachers);
            }
            if ($cache) {
                $cache->save('ClassesHaveTeachers', $classesHaveTeachers);
            }
        }
        if (!empty($sort)) {
            $field = array_keys($sort)[0];
            $sortingOrder = $sort[$field];
            $data = $this->sort($classesHaveTeachers, $field, $sortingOrder);
        } else {
            $data = $classesHaveTeachers;
        }
        if ($limit != null) {
            return array_slice($data, 0, $limit);
        }
        return $data;
    }
}
