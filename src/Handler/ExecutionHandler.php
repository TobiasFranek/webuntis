<?php
declare(strict_types=1);

namespace Webuntis\Handler;

use Webuntis\Models\AbstractModel;
use Webuntis\Models\Interfaces\CachableModelInterface;
use Webuntis\Repositories\Repository;
use Webuntis\Handler\Interfaces\ExecutionHandlerInterface;
use Webuntis\CacheBuilder\CacheBuilder;

/**
 * The ExecutionHandler represents the brigde between the api and the Repository/Model.
 * It receives the raw data from the api and parses it into the according model and caches it
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class ExecutionHandler implements ExecutionHandlerInterface {

    /**
     * @var object|bool
     */
    private $cache;

    public function __construct() 
    {
        $cacheBuilder = new CacheBuilder();
        $this->cache = $cacheBuilder->create();
    }

    /**
     * executes the given command with the right instance, model etc.
     * @param Repository $repository
     * @param array $params
     * @return AbstractModel[]
     */
    public function execute(Repository $repository, array $params) : array 
    {
        $model = $repository->getModel();
        $interfaces = class_implements($model);
        if ($this->cache && $this->cache->contains($model::METHOD) && isset($interfaces[CachableModelInterface::class])) {
            $data = $this->cache->fetch($model::METHOD);
        } else {
            $result = $repository->getInstance()->getClient()->call($model::METHOD, $params);
            $data = $repository->parse($result);

            if ($this->cache && isset($interfaces[CachableModelInterface::class])) {
                if ($model::CACHE_LIFE_TIME) {
                    $this->cache->save($model::METHOD, $data, $model::CACHE_LIFE_TIME);
                } else {
                    $this->cache->save($model::METHOD, $data);
                }
            }
        }
        return $data;
    }
}