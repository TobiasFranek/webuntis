<?php
declare(strict_types=1);

namespace Webuntis\Repositories;

use Webuntis\Models\Classes;
use Webuntis\Query\Query;
use Webuntis\Handler\ExecutionHandler;

/**
 * ClassHasTeachersRepository
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class ClassHasTeachersRepository extends Repository {

    /**
     * @param array $sort
     * @param int $limit
     * @return array
     */
    public function findAll(array $sort = [], ?int $limit = null) : array 
    {
        $classesHaveTeachers = [];
        if ($this->cache && $this->cache->contains('ClassesHaveTeachers')) {
            $classesHaveTeachers = $this->cache->fetch('ClassesHaveTeachers');
        } else {
            $query = new Query();
            
            $classes = $this->executionHandler->execute(new Repository(Classes::class), []);

            foreach ($classes as $class) {
                $class = $class->serialize();
                $class['teachers'] = [];
                $classesHaveTeachers[] = new $this->model($class);
            }
            $schoolyear = $query->get('Schoolyears')->getCurrentSchoolyear();
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
            if ($this->cache) {
                $this->cache->save('ClassesHaveTeachers', $classesHaveTeachers);
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
