<?php
declare(strict_types=1);

namespace Webuntis\Repositories;

use Webuntis\Configuration\WebuntisConfiguration;
use Webuntis\Exceptions\RepositoryException;
use Webuntis\Models\Interfaces\CachableModelInterface;
use Webuntis\Handler\ExecutionHandler;
use Webuntis\Handler\Interfaces\ExecutionHandlerInterface;
use Webuntis\Models\AbstractModel;
use Webuntis\Webuntis;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Webuntis\WebuntisFactory;
use Webuntis\CacheBuilder\CacheBuilder;

/**
 * The Repository receives the raw data from the ExectuionHandler
 * it is responsible for parsing, filtering, sorting this raw data
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class Repository {

    /**
     * @var string
     */
    protected $model;

    /**
     * @var Webuntis
     */
    protected $instance;

    /**
     * @var object|bool
     */
    protected $cache;

    /**
     * @var ExecutionHandlerInterface
     */
    protected $executionHandler;

    /**
     * Repository constructor.
     * @param string $model
     */
    public function __construct(string $model, ExecutionHandlerInterface $executionHandler = null) {
        $this->model = $model;

        if ($executionHandler) {
            $this->executionHandler = $executionHandler;
        } else {
            $this->executionHandler = new ExecutionHandler();
        }

        $cacheBuilder = new CacheBuilder();

        $this->cache = $cacheBuilder->create();

        $this->instance = WebuntisFactory::create($model);
        AnnotationRegistry::registerLoader('class_exists');
    }

    /**
     * return all objects that have been searched for
     * @param array $params
     * @param array $sort
     * @param int $limit
     * @return AbstractModel[]
     * @throws RepositoryException
     */
    public function findBy(array $params, array $sort = [], int $limit = null) : array 
    {
        if (empty($params)) {
            throw new RepositoryException('missing parameters');
        }
        $data = $this->findAll();

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
     * returns all objects it could find
     * @param array $sort
     * @param int $limit
     * @return AbstractModel[]
     */
    public function findAll(array $sort = [], int $limit = null) : array 
    {
        $data = $this->executionHandler->execute($this, []);
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

    /**
     * parses the given objects
     * @param array $result
     * @return AbstractModel[]
     */
    public function parse(array $result) : array
    {
        $data = [];
        foreach ($result as $key => $value) {
            /** @var AbstractModel $newObj */
            $newObj = new $this->model($value);
            $data[] = $newObj;
        }
        return $data;
    }

    /**
     * searches the $data array with the given params
     * @param array $data
     * @param array $params
     * @return AbstractModel[]
     * @throws RepositoryException
     */
    protected function find(array $data, array $params) : array 
    {
        if (!empty($data)) {
            if ($this->cache && $this->cache->contains($this->model::METHOD . '.' . serialize($params))) {
                return $this->cache->fetch($this->model::METHOD . '.' . serialize($params));
            }
            foreach ($params as $key => $value) {
                $temp = [];
                $keys = explode(":", $key);
                $key = $keys[0];
                if (isset($data[0]) && isset($data[0]->serialize()[$key])) {
                    foreach ($data as $key2 => $value2) {
                        if (count($keys) > 1 && (!isset(WebuntisConfiguration::getConfig()[$this->instance->getContext()]['ignore_children']) || !WebuntisConfiguration::getConfig()[$this->instance->getContext()]['ignore_children'])) {
                            $tempKeys = $keys;
                            $tempKeys = array_splice($tempKeys, 1, count($tempKeys) - 1);
                            $tempKeys = implode(':', $tempKeys);
                            if (!empty($this->find($value2->get($key), [$tempKeys => $value]))) {
                                $temp[] = $value2;
                            }
                        } else {
                            if ($value !== '' && $this->validateDate(substr(strval($value), 1))) {
                                if ($this->startsWith($value, '<')) {
                                    if (new \DateTime($value2->serialize()[$key]) <= new \DateTime(substr(strval($value), 1))) {
                                        $temp[] = $value2;
                                    }
                                } else if ($this->startsWith($value, '>')) {
                                    if (new \DateTime($value2->serialize()[$key]) >= new \DateTime(substr(strval($value), 1))) {
                                        $temp[] = $value2;
                                    }
                                } else {
                                    throw new RepositoryException('wrong date format');
                                }
                            }
                            if ($value2->serialize()[$key] == $value) {
                                $temp[] = $value2;
                            } else if ($this->endsWith(strval($value), '%') && $this->startsWith(strval($value), '%')) {
                                if ($this->contains($value2->serialize()[$key], substr(strval($value), 1, strlen(strval($value)) - 2))) {
                                    $temp[] = $value2;
                                }
                            } else if ($this->startsWith(strval($value), '%')) {
                                if ($this->endsWith(strval($value2->serialize()[$key]), substr(strval($value), 1, strlen(strval($value))))) {
                                    $temp[] = $value2;
                                }
                            } else if ($this->endsWith(strval($value), '%')) {
                                if ($this->startsWith(strval($value2->serialize()[$key]), substr(strval($value), 0, strlen(strval($value)) - 1))) {
                                    $temp[] = $value2;
                                }
                            }
                        }
                    }
                    $data = $temp;
                } else {
                    throw new RepositoryException('the parameter ' . $key . ' doesn\'t exist');
                }
            }
        }
        if ($this->cache) {
            $this->cache->save($this->model::METHOD . '.' . serialize($params), $data);
        }
        return $data;
    }

    /**
     * validates the given date
     * @param string $data
     * @return bool
     */
    private function validateDate(string $date) : bool 
    {
        $d = \DateTime::createFromFormat('Y-m-d', $date);
        $d2 = \DateTime::createFromFormat('Y-m-d H:i', $date);
        return $d && $d->format('Y-m-d') === $date || $d2 && $d2->format('Y-m-d H:i') === $date;
    }

    /**
     * sort the given data array, that contains of AbstractModels
     * @param array $data
     * @param string $field
     * @param string $sortingOrder
     * @return AbstractModel[]
     */
    public function sort(array $data, string $field, string $sortingOrder) : array 
    {
        usort($data, $this->sortingAlgorithm($field, $sortingOrder));
        return $data;
    }

    /**
     * generates the right sorting lambda for the usort() method
     * @param string $key
     * @param string $sortingOrder
     * @return \Closure
     */
    private function sortingAlgorithm(string $key, string $sortingOrder) : callable 
    {
        $keys = explode(':', $key);
        $offset = null;
        if (count($keys) > 1) {
            $offset = $keys[0];
            $key = $keys[1];
        } else {
            $key = $keys[0];
        }
        if ($sortingOrder == 'ASC') {
            return function($a, $b) use ($key, $offset) {
                if ($offset) {
                    if (gettype($a->serialize()[$offset][0][$key]) == 'string' && gettype($b->serialize()[$offset][0][$key] == 'string')) {
                        return strcmp($a->serialize()[$offset][0][$key], $b->serialize()[$offset][0][$key]);
                    } else {
                        return $b->serialize()[$offset][0][$key] < $a->serialize()[$offset][0][$key];
                    }
                } else {
                    if (gettype($a->serialize()[$key]) == 'string' && gettype($b->serialize()[$key] == 'string')) {
                        return strcmp($a->serialize()[$key], $b->serialize()[$key]);
                    } else {
                        return $b->serialize()[$key] < $a->serialize()[$key];
                    }
                }
            };
        } else if ($sortingOrder == 'DESC') {
            return function($a, $b) use ($key, $offset) {
                if ($offset) {
                    if (gettype($a->serialize()[$offset][0][$key]) == 'string' && gettype($b->serialize()[$offset][0][$key] == 'string')) {
                        return strcmp($b->serialize()[$offset][0][$key], $a->serialize()[$offset][0][$key]);
                    } else {
                        return strcmp($b->serialize()[$offset][0][$key], $a->serialize()[$offset][0][$key]);
                    }
                } else {
                    if (gettype($a->serialize()[$key]) == 'string' && gettype($b->serialize()[$key] == 'string')) {
                        return $b->serialize()[$key] > $a->serialize()[$key];
                    } else {
                        return $b->serialize()[$key] > $a->serialize()[$key];
                    }
                }
            };
        } else {
            throw new RepositoryException('sort order ' . $sortingOrder . ' does not exist');
        }
    }

    /**
     * @return Webuntis
     */
    public function getInstance() : object 
    {
        return $this->instance;
    }

    /**
     * @return string
     */
    public function getModel() : string 
    {
        return $this->model;
    }

    /**
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    protected function startsWith(string $haystack, string $needle) : bool 
    {
        return substr($haystack, 0, strlen($needle)) == $needle;
    }

    /**
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    protected function endsWith(string $haystack, string $needle) : bool 
    {
        return substr($haystack, strlen($haystack) - strlen($needle), strlen($haystack)) == $needle;
    }

    /**
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    protected function contains(string $haystack, string $needle) : bool 
    {
        return strpos($haystack, $needle) !== false;
    }
}