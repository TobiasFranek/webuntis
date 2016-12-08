<?php
/**
 * Created by PhpStorm.
 * User: Tobias
 * Date: 05.11.16
 * Time: 11:39
 */

namespace Webuntis\Repositories;


use Doctrine\Common\Cache\ApcuCache;
use Webuntis\Models\Classes;
use Webuntis\Query\Query;
use Webuntis\Util\ExecutionHandler;

class ClassHasTeachersRepository extends Repository {

    /**
     * @param array $sort
     * @return array
     */
    public function findAll(array $sort = []) {
        $cache = new ApcuCache();
        $classesHaveTeachers = [];
        if ($cache->contains('ClassesHaveTeachers')) {
            $classesHaveTeachers = $cache->fetch('ClassesHaveTeachers');
        } else {
            $query = new Query();

            $classes = ExecutionHandler::execute(Classes::class, $this->instance, []);


            foreach ($classes as $class) {
                $class['teachers'] = [];
                $classesHaveTeachers[] = new $this->model($class);
            }
            $schoolyear = $query->get('Schoolyear')->findAll();


            foreach ($classesHaveTeachers as $key => $value) {
                $periods = $query->get('Period')->findAll($value->getId(), 1, date_format($schoolyear->getStartDate(), 'Ymd'), date_format($schoolyear->getEndDate(), 'Ymd'));
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
        if(!empty($sort)) {
            $field = array_keys($sort)[0];
            $sortingOrder = $sort[$field];
            return $this->sort($classesHaveTeachers, $field, $sortingOrder);
        }else {
            return $classesHaveTeachers;
        }
    }
}