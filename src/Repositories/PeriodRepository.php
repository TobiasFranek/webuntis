<?php
declare(strict_types=1);

namespace Webuntis\Repositories;

use Webuntis\Exceptions\RepositoryException;
use Webuntis\Handler\ExecutionHandler;
use Webuntis\Models\AbstractModel;

/**
 * PeriodRepository
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class PeriodRepository extends Repository {

    /**
     * gets the period object from the current day or the given date and can be filtered
     * @param array $params
     * @param array $sort
     * @param int $limit
     * @param int $id
     * @param int $type
     * @param string $startDate
     * @param string $endDate
     * @return AbstractModel[]
     * @throws RepositoryException
     */
    public function findBy(array $params, array $sort = [], int $limit = null, int $id = null, int $type = null, string $startDate = null, string $endDate = null) : array
    {
        if (empty($params)) {
            throw new RepositoryException('missing parameters');
        }
        if ($type == null) {
            $type = $this->instance->getCurrentUserType();
        }
        if ($id == null) {
            $id = $this->instance->getCurrentUser()->getId();
        }
        if($startDate && $endDate) {
            $startDate = new \DateTime($startDate);
            $endDate = new \DateTime($endDate);
            $startDate = date_format($startDate, 'Ymd');
            $endDate = date_format($endDate, 'Ymd');
            $data = $this->executionHandler->execute($this, ['id' => $id, 'type' => $type, 'startDate' => $startDate, 'endDate' => $endDate]);
        }else if ($startDate || $endDate) {
            throw new RepositoryException('missing parameter endDate or startDate');
        }else {
            $data = $this->executionHandler->execute($this, ['id' => $id, 'type' => $type]);
        }

        if(!empty($sort)) {
            $field = array_keys($sort)[0];
            $sortingOrder = $sort[$field];
            $data = $this->find($data, $params);
            $data = $this->sort($data, $field, $sortingOrder);
        }else {
            $data = $this->find($data, $params);
        }
        if($limit != null) {
            return array_slice($data, 0, $limit);
        }
        return $data;
    }

    /**
     * gets the period object from the current day or from the given date
     * @param array $sort
     * @param int $limit
     * @param int $id
     * @param int $type
     * @param string $startDate
     * @param string $endDate
     * @return AbstractModel[]
     */
    public function findAll(array $sort = [], int $limit = null, int $id = null, int $type = null, string $startDate = null, string $endDate = null) : array 
    {
        if ($type == null) {
            $type = $this->instance->getCurrentUserType();
        }
        if ($id == null) {
            $id = $this->instance->getCurrentUser()->getId();
        }
        if($startDate && $endDate) {
            $startDate = new \DateTime($startDate);
            $endDate = new \DateTime($endDate);
            $startDate = date_format($startDate, 'Ymd');
            $endDate = date_format($endDate, 'Ymd');
            $data = $this->executionHandler->execute($this, ['id' => $id, 'type' => $type, 'startDate' => $startDate, 'endDate' => $endDate]);
        }else if ($startDate || $endDate){
            throw new RepositoryException('missing parameter endDate or startDate');
        }else {
            $data = $this->executionHandler->execute($this, ['id' => $id, 'type' => $type]);
        }
        if(!empty($sort)) {
            $field = array_keys($sort)[0];
            $sortingOrder = $sort[$field];
            $data = $this->sort($data, $field, $sortingOrder);
        }
        if($limit != null) {
            $data = array_slice($data, 0, $limit);
        }
        return $data;
    }

}