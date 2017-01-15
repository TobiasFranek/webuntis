<?php
/**
 * Created by PhpStorm.
 * Author: Tobias Franek <tobias.franek@gmail.com>
 * Project: webuntis
 * Date: 15.01.17
 * Time: 19:26
 */

namespace Webuntis\Repositories;


use Webuntis\Exceptions\RepositoryException;
use Webuntis\Models\AbstractModel;
use Webuntis\Util\ExecutionHandler;

class SubstitutionsRepository extends Repository {
    /**
     * gets the period object from the current day or the given date and can be filtered
     * @param array $params
     * @param array $sort
     * @param null $limit
     * @param int $department
     * @param null $startDate
     * @param null $endDate
     * @return AbstractModel[]
     * @internal param null $id
     * @internal param null $type
     */
    public function findBy(array $params, array $sort = [], $limit = null, $department = 0, $startDate, $endDate) {
        if (empty($params)) {
            throw new RepositoryException('missing parameters');
        }
        if ($startDate && $endDate) {
            $startDate = new \DateTime($startDate);
            $endDate = new \DateTime($endDate);
            $startDate = date_format($startDate, 'Ymd');
            $endDate = date_format($endDate, 'Ymd');
            $data = ExecutionHandler::execute($this, ['departmentId' => $department, 'startDate' => $startDate, 'endDate' => $endDate]);
        } else {
            throw new RepositoryException('missing parameter endDate or startDate');
        }

        if (!empty($sort)) {
            $field = array_keys($sort)[0];
            $sortingOrder = $sort[$field];
            $data = $this->find($data, $params);
            $data = $this->sort($data, $field, $sortingOrder);
        } else {
            $data = $this->find($data, $params);
        }
        if ($limit != null) {
            return array_slice($data, 0, $limit);
        }
        return $data;
    }

    /**
     * gets the period object from the current day or from the given date
     * @param array $sort
     * @param null $limit
     * @param int $department
     * @param null $startDate
     * @param null $endDate
     * @return AbstractModel[]
     * @internal param null $id
     * @internal param null $type
     */
    public function findAll(array $sort = [], $limit = null, $department = 0, $startDate, $endDate) {
        if ($startDate && $endDate) {
            $startDate = new \DateTime($startDate);
            $endDate = new \DateTime($endDate);
            $startDate = date_format($startDate, 'Ymd');
            $endDate = date_format($endDate, 'Ymd');
            $data = ExecutionHandler::execute($this, ['departmentId' => $department, 'startDate' => $startDate, 'endDate' => $endDate]);
        } else {
            throw new RepositoryException('missing parameter endDate or startDate');
        }
        if (!empty($sort)) {
            $field = array_keys($sort)[0];
            $sortingOrder = $sort[$field];
            $data = $this->sort($data, $field, $sortingOrder);
        }
        if ($limit != null) {
            $data = array_slice($data, 0, $limit);
        }
        return $data;
    }
}