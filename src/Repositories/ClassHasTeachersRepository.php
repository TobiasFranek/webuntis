<?php
/**
 * Created by PhpStorm.
 * User: Tobias
 * Date: 05.11.16
 * Time: 11:39
 */

namespace Webuntis\Repositories;


use Webuntis\Models\Classes;
use Webuntis\Query\Query;
use Webuntis\Util\ExecutionHandler;

class ClassHasTeachersRepository extends Repository {

    /**
     * @param array $sort
     * @param null $limit
     * @return array
     */
    public function findAll(array $sort = [], $limit = null) {
        $cache = self::getCache();
        $classesHaveTeachers = [];
        if ($cache->contains('ClassesHaveTeachers')) {
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
            $cache->save('ClassesHaveTeachers', $classesHaveTeachers);
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