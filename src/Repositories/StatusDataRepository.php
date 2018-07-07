<?php
declare(strict_types=1);

namespace Webuntis\Repositories;
use Webuntis\Models\Schoolyears;
use Webuntis\Models\CurrentSchoolyear;
use Webuntis\Handler\ExecutionHandler;

/**
 * StatusDataRepository
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class StatusDataRepository extends Repository {

    /**
     * {@inheritdoc}
     */
    public function parse(array $result) : array 
    {
        return [new $this->model($result)];
    }
}