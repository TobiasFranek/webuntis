<?php
declare(strict_types=1);

namespace Webuntis\Repositories;

use Webuntis\Models\AbstractModel;
use Webuntis\Models\Exams;
use Webuntis\Models\ExamTypes;
use Webuntis\Query\Query;
use Webuntis\Handler\ExecutionHandler;

/**
 * ExamsRepository
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class ExamsRepository extends Repository {

    /**
     * @param array $sort
     * @param int $limit
     * @return AbstractModel[]
     */
    public function findAll(array $sort = [], ?int $limit = null) : array
    {
        $query = new Query();
        if ($this->cache && $this->cache->contains('Exams')) {
            $data = $this->cache->fetch('Exams');
        } else {
            $examTypes = $this->executionHandler->execute(new Repository(ExamTypes::class), []);
            $exams = [];
            $schoolyear = $query->get('Schoolyears')->getCurrentSchoolyear();
            foreach ($examTypes as $value) {
                $exams[] = $this->executionHandler->execute($this, ['examTypeId' => $value->serialize()['id'], 'startDate' => date_format(new \DateTime(), 'Ymd'), 'endDate' => date_format($schoolyear->getEndDate(), 'Ymd')]);
            }
            $result = [];
            foreach ($exams as $value) {
                if (!empty($value)) {
                    foreach ($value as $value2) {
                        $result[] = $value2;
                    }
                }
            }
            if (!empty($sort)) {
                $field = array_keys($sort)[0];
                $sortingOrder = $sort[$field];
                $data = $this->sort($result, $field, $sortingOrder);
            } else {
                $data = $result;
            }
            if ($limit !== null) {
                return array_slice($data, 0, $limit);
            }
            if ($this->cache) {
                $this->cache->save('Exams', $data, 604800);
            }
        }

        return $data;
    }
}
