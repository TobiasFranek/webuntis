<?php
declare(strict_types=1);

namespace Webuntis\Repositories;

use Webuntis\Repositories\Repository;
use Webuntis\Query\Query;
use Webuntis\Exceptions\RepositoryException;
use Webuntis\Configuration\WebuntisConfiguration;

/**
 * AbsencesRepository
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class AbsencesRepository extends Repository
{

        /**
         * return all Absences that have been searched for
         * @param array $params
         * @param array $sort
         * @param int $limit
         * @return array
         * @throws RepositoryException
         */
    public function findBy(array $params, array $sort = [], int $limit = null, $startDate = null, \DateTime $endDate = null, bool $lazy = false) : array 
    {
        if (empty($params)) {
            throw new RepositoryException('missing parameters');
        }
        $data = $this->findAll($sort, $limit, $startDate, $endDate, $lazy);

        if (!empty($sort)) {
            $field = array_keys($sort)[0];
            $sortingOrder = $sort[$field];
            $data = $this->find($data, $params);
            $data = $this->sort($data, $field, $sortingOrder);
        } else {
            $data = $this->find($data, $params);
        }
        if ($limit !== null) {
            return array_slice($data, 0, $limit);
        }
        return $data;
    }

    /**
     * return all Absences found
     * @param array $sort
     * @param int $limit 
     * @return array
     */
    public function findAll(array $sort = [], int $limit = null, \DateTime $startDate = null, \DateTime $endDate = null, bool $lazy = false) : array 
    {
        $configName = WebuntisConfiguration::getConfigNameByModel(new $this->model());
        $config = WebuntisConfiguration::getConfig();
        if ($lazy) {
            $config[$configName]['ignore_children'] = true;
            WebuntisConfiguration::setConfig($config);
        }
        if ($startDate && $endDate) {
            $data = $this->executionHandler->execute($this, ['options' => 
                [
                    'startDate' => date_format($startDate, 'Ymd'), 
                    'endDate' => date_format($endDate, 'Ymd')
                ]
            ]);
        } else {
            $query = new Query();
            $schoolyear = $query->get('Schoolyears')->getCurrentSchoolyear();
            $data = $this->executionHandler->execute($this, ['options' => 
                [
                    'startDate' => date_format($schoolyear->getStartDate(), 'Ymd'),
                    'endDate' => date_format($schoolyear->getEndDate(), 'Ymd')
                ]
            ]);
        }
        if (!empty($sort)) {
            $field = array_keys($sort)[0];
            $sortingOrder = $sort[$field];
            $data = $this->sort($data, $field, $sortingOrder);
        }
        if ($limit !== null) {
            $data = array_slice($data, 0, $limit);
        }
        if ($lazy) {
            unset($config[$configName]['ignore_children']);
            WebuntisConfiguration::setConfig($config);
        }
        return $data;
    }

    /**
     * parses the given objects
     * @param array $result
     * @return AbstractModel[]
     */
    public function parse(array $result) : array
    {
        $data = [];
        foreach ($result['periodsWithAbsences'] as $key => $value) {
            /** @var AbstractModel $newObj */
            $newObj = new $this->model($value);
            $data[] = $newObj;
        }
        return $data;
    }
}
