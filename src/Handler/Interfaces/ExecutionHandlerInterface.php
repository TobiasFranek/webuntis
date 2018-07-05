<?php
declare(strict_types=1);

namespace Webuntis\Handler\Interfaces;

use Webuntis\Repositories\Repository;

/**
 * The Interface for the ExecutionHandler
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
interface ExecutionHandlerInterface {

    /**
     * executes the given command with the right instance, model etc.
     * @param Repository $repository
     * @param array $params
     * @return AbstractModel[]
     */
    public function execute(Repository $repository, array $params) : array;
}