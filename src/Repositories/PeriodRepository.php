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
    public function findBy(array $params, array $sort = [], int $limit = null, array $options = []) : array
    {
        if (empty($params)) {
            throw new RepositoryException('missing parameters');
        }
        $data = $this->findAll([], null, $options);

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
     * gets the period object from the current day or from the given date
     * @param array $sort
     * @param int $limit
     * @param int $id
     * @param int $type
     * @param string $startDate
     * @param string $endDate
     * @return AbstractModel[]
     */
    public function findAll(array $sort = [], int $limit = null, array $options = []) : array
    {
        if (!isset($options['options']['element']['type'])) {
            $options['options']['element']['type'] = $this->instance->getCurrentUserType();
        }
        if (!isset($options['options']['element']['id'])) {
            $options['options']['element']['id'] = $this->instance->getCurrentUser()->getId();
        }
        $data = $this->executionHandler->execute($this, $options);

        if (!empty($sort)) {
            $field = array_keys($sort)[0];
            $sortingOrder = $sort[$field];
            $data = $this->sort($data, $field, $sortingOrder);
        }
        if ($limit !== null) {
            $data = array_slice($data, 0, $limit);
        }
        return $data;
    }

}
