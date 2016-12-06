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


namespace Webuntis\Models;

use Webuntis\Query\Query;

/**
 * Class Exams
 * @package Webuntis\Models
 * @author Tobias Franek <tobias.franek@gmail.com>
 */
class Exams extends AbstractModel {

    /**
     * @var array
     */
    private $classes = [];

    /**
     * @var array
     */
    private $teachers = [];

    /**
     * @var array
     */
    private $students = [];

    /**
     * @var int
     */
    private $subject;

    /**
     * @var \DateTime
     */
    private $startDate;

    /**
     * @var \DateTime
     */
    private $endDate;

    /**
     * @var string
     */
    const METHOD = 'getExams';

    /**
     * parses the given data to the model
     * @param $data
     */
    public function parse($data) {
        $query = new Query();
        $this->subject = $query->get('subjects')->findBy(['id' => $data['subject']])[0];
        if (!empty($data['classes'])) {
            foreach ($data['classes'] as $value) {
                $this->classes[] = $query->get('classes')->findBy(['id' => $value]);
            }
        }
        if (!empty($data['teachers'])) {
            foreach ($data['teachers'] as $value) {
                $this->teachers[] = $query->get('teachers')->findBy(['id' => $value]);;
            }
        }
        if (!empty($data['students'])) {
            foreach ($data['students'] as $value) {
                $this->students[] = $query->get('students')->findBy(['id' => $value]);;
            }
        }
        if (strlen($data['startTime']) < 4) {
            $this->startDate = new \DateTime($data['date'] . ' ' . '0' . substr($data['startTime'], 0, 1) . ':' . substr($data['startTime'], strlen($data['startTime']) - 2, strlen($data['startTime'])));
        } else {
            $this->startDate = new \DateTime($data['date'] . ' ' . substr($data['startTime'], 0, 2) . ':' . substr($data['startTime'], strlen($data['startTime']) - 2, strlen($data['startTime'])));
        }
        if (strlen($data['endTime']) < 4) {
            $this->endDate = new \DateTime($data['date'] . ' ' . '0' . substr($data['endTime'], 0, 1) . ':' . substr($data['endTime'], strlen($data['endTime']) - 2, strlen($data['endTime'])));
        } else {
            $this->endDate = new \DateTime($data['date'] . ' ' . substr($data['endTime'], 0, 2) . ':' . substr($data['endTime'], strlen($data['endTime']) - 2, strlen($data['endTime'])));
        }
    }

    /**
     * serializes the model
     * @return array
     */
    public function serialize() {
        return [
            'id' => $this->getId(),
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'subject' => $this->subject->serialize(),
            'teachers' => $this->serializeObj($this->teachers),
            'students' => $this->serializeObj($this->students),
            'classes' => $this->serializeObj($this->classes)
        ];
    }
    /**
     * serializes the classes
     * @param $objs array
     * @return array
     */
    private function serializeObj(array $objs) {
        $result = [];
        foreach ($objs as $value) {
            $result[] = $value->serialize();
        }
        return $result;
    }

}