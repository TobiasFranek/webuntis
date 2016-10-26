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

/**
 * Class PeriodRepository
 * @package Webuntis\Repositorys
 * @author Tobias Franek <tobias.franek@gmail.com>
 */
class PeriodRepository extends Repository {

    /**
     * get the period objects from the current day but with an additional filter
     * @param array $params
     * @param null $id
     * @param null $type
     * @return AbstractModel[]
     */
    public function getSomeFromCurrentDay(array $params, $id = null, $type = null) {
        if (empty($params)) {
            throw new RepositoryException('missing parameters');
        }
        if ($type == null) {
            $type = $this->instance->getCurrentUserType();
        }
        if ($id == null) {
            $id = $this->instance->getCurrentUser()->getId();
        }
        $result = ExecutionHandler::execute($this->model, $this->instance, ['id' => $id, 'type' => $type]);

        $data = $this->parse($result);

        return $this->find($data, $params);
    }

    public function findAll() {
        //todo findall method
    }

    public function findBy() {
        //todo findby method
    }

    /**
     * get the period objects from the current day
     * @param null $id
     * @param null $type
     * @return AbstractModel[]
     */
    public function getAllFromCurrentDay($id = null, $type = null) {
        if ($type == null) {
            $type = $this->instance->getCurrentUserType();
        }
        if ($id == null) {
            $id = $this->instance->getCurrentUser()->getId();
        }

        $result = ExecutionHandler::execute($this->model, $this->instance, ['id' => $id, 'type' => $type]);
        return $this->parse($result);
    }
}