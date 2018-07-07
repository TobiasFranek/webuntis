<?php
declare(strict_types=1);

namespace Webuntis\Repositories;

use Webuntis\Repositories\Repository;

/**
 * ExamsRepository
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class LastImportTimeRepository extends Repository
{
    /**
     * {@inheritdoc}
     */
    public function parse(array $result) : array 
    {
        return [new $this->model(['time' => $result[0]])];
    }
}
