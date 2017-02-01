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

use Webuntis\Models\AbstractModel;
use Webuntis\Models\Exams;
use Webuntis\Models\ExamTypes;
use Webuntis\Query\Query;
use Webuntis\Util\ExecutionHandler;

/**
 * Class ExamsRepository
 * @package Webuntis\Repositories
 * @author Tobias Franek <tobias.franek@gmail.com>
 */
class ExamsRepository extends Repository {

    /**
     * @param array $sort
     * @param null $limit
     * @return AbstractModel[]
     */
    public function findAll(array $sort = [], $limit = null) {
        $query = new Query();
        $cache = self::getCache();
        if ($cache->contains('Exams')) {
            $data = $cache->fetch('Exams');
        } else {
            $examTypes = ExecutionHandler::execute(new Repository(ExamTypes::class), []);
            $exams = [];
            $schoolyear = $query->get('Schoolyear')->findAll();
            foreach ($examTypes as $value) {
                $exams[] = ExecutionHandler::execute($this, ['examTypeId' => $value->serialize()['id'], 'startDate' => date_format(new \DateTime(), 'Ymd'), 'endDate' => date_format($schoolyear->getEndDate(), 'Ymd')]);
            }
            $result = [];
            foreach ($exams as $value) {
                if (!empty($value)) {
                    foreach ($value as $value2) {
                        $result[] = $value2;
                    }
                }
            }
            if (!empty($sort)) {
                $field = array_keys($sort)[0];
                $sortingOrder = $sort[$field];
                $data = $this->sort($result, $field, $sortingOrder);
            } else {
                $data = $result;
            }
            if ($limit != null) {
                return array_slice($data, 0, $limit);
            }
            $cache->save('Exams', $data, 604800);
        }

        return $data;
    }
}