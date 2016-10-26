<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

namespace Webuntis\Repositories;

use Webuntis\Exceptions\RepositoryException;
use Webuntis\Util\ExecutionHandler;
use Webuntis\Models\AbstractModel;
use Webuntis\Webuntis;
use Webuntis\WebuntisFactory;

/**
 * Class Repository
 * @package Webuntis\Repositorys
 * @author Tobias Franek <tobias.franek@gmail.com>
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
     * Repository constructor.
     * @param $model
     */
    public function __construct($model) {
        $this->model = $model;
        $this->instance = WebuntisFactory::create($model);
    }

    /**
     * return all objects that have been searched for
     * @param array $params
     * @return \Webuntis\Models\AbstractModel[]
     */
    public function findBy(array $params) {
        if (empty($params)) {
            throw new RepositoryException('missing parameters');
        }
        $result = ExecutionHandler::execute($this->model, $this->instance, []);

        /** @var AbstractModel[] $data */
        $data = $this->parse($result);

        return $this->find($data, $params);
    }

    /**
     * returns all objects it could find
     * @return AbstractModel[]
     */
    public function findAll() {
        $result = ExecutionHandler::execute($this->model, $this->instance, []);
        return $this->parse($result);
    }

    /**
     * parses the given objects
     * @param AbstractModel[] $result
     * @return AbstractModel[]
     */
    protected function parse($result) {
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
     * @param AbstractModel[] $data
     * @param array $params
     * @return AbstractModel[]
     */
    protected function find($data, $params) {
        foreach ($params as $key => $value) {
            $temp = [];
            if (isset($data[0]->serialize()[$key])) {
                foreach ($data as $key2 => $value2) {
                    if ($value2->serialize()[$key] == $value) {
                        $temp[] = $value2;
                    }
                }
                $data = $temp;
            } else {
                throw new RepositoryException('the parameter ' . $key . ' doesn\'t exist');
            }
        }
        return $data;
    }
}