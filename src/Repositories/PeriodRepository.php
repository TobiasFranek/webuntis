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
     * gets the period object from the current day or the given date and can be filtered
     * @param array $params
     * @param array $sort
     * @param null $limit
     * @param null $id
     * @param null $type
     * @param null $startDate
     * @param null $endDate
     * @return AbstractModel[]
     */
    public function findBy(array $params, array $sort = [],$limit = null, $id = null, $type = null, $startDate = null, $endDate = null) {
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
            $data = ExecutionHandler::execute($this, ['id' => $id, 'type' => $type, 'startDate' => $startDate, 'endDate' => $endDate]);
        }else if ($startDate || $endDate) {
            throw new RepositoryException('missing parameter endDate or startDate');
        }else {
            $data = ExecutionHandler::execute($this, ['id' => $id, 'type' => $type]);
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
     * @param null $limit
     * @param null $id
     * @param null $type
     * @param null $startDate
     * @param null $endDate
     * @return AbstractModel[]
     */
    public function findAll(array $sort = [],$limit = null, $id = null, $type = null, $startDate = null, $endDate = null) {
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
            $data = ExecutionHandler::execute($this, ['id' => $id, 'type' => $type, 'startDate' => $startDate, 'endDate' => $endDate]);
        }else if ($startDate || $endDate){
            throw new RepositoryException('missing parameter endDate or startDate');
        }else {
            $data = ExecutionHandler::execute($this, ['id' => $id, 'type' => $type]);
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