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

use Webuntis\Exceptions\ModelException;
use Webuntis\Query\Query;

/**
 * Class Period
 * @package Webuntis\Models
 * @author Tobias Franek <tobias.franek@gmail.com>
 */
class Period extends AbstractModel {

    /**
     * @var \DateTime
     */
    private $startTime;

    /**
     * @var \DateTime
     */
    private $endTime;

    /**
     * @var Classes[]
     */
    private $classes = [];

    /**
     * @var Teachers[]
     */
    private $teachers = [];

    /**
     * @var Subjects[]
     */
    private $subjects = [];

    /**
     * @var Rooms[]
     */
    private $rooms = [];

    /**
     * @var string
     */
    private $code = 'normal';

    /**
     * @var string
     */
    private $type = 'lesson';

    /**
     * @var string
     */
    const METHOD = "getTimetable";

    /**
     * return the startTime
     * @return \DateTime
     */
    public function getStartTime() {
        return $this->startTime;
    }

    /**
     * serializes the object and returns an array with the objects values
     * @return array
     */
    public function serialize() {
        return [
            'id' => $this->getId(),
            'code' => $this->code,
            'type' => $this->type,
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
            'classes' => $this->serializeObj($this->classes),
            'teachers' => $this->serializeObj($this->teachers),
            'subjects' => $this->serializeObj($this->subjects),
            'rooms' => $this->serializeObj($this->rooms)
        ];
    }

    /**
     * parses the given data from the json rpc api to the right format for the object
     * @param array $data
     */
    protected function parse($data) {
        $query = new Query();
        $this->setId($data['id']);
        if (strlen($data['startTime']) < 4) {
            $this->startTime = new \DateTime($data['date'] . ' ' . '0' . substr($data['startTime'], 0, 1) . ':' . substr($data['startTime'], strlen($data['startTime']) - 2, strlen($data['startTime'])));
        } else {
            $this->startTime = new \DateTime($data['date'] . ' ' . substr($data['startTime'], 0, 2) . ':' . substr($data['startTime'], strlen($data['startTime']) - 2, strlen($data['startTime'])));
        }
        if (strlen($data['endTime']) < 4) {
            $this->endTime = new \DateTime($data['date'] . ' ' . '0' . substr($data['endTime'], 0, 1) . ':' . substr($data['endTime'], strlen($data['endTime']) - 2, strlen($data['endTime'])));
        } else {
            $this->endTime = new \DateTime($data['date'] . ' ' . substr($data['endTime'], 0, 2) . ':' . substr($data['endTime'], strlen($data['endTime']) - 2, strlen($data['endTime'])));
        }

        if(isset($data['code'])) {
            $this->code = $data['code'];
        }

        if(isset($data['lstype'])) {
            switch ($data['lstype']){
                case 'ls':
                    $this->type = 'lesson';
                    break;
                case 'oh':
                    $this->type = 'office hour';
                    break;
                case 'sb':
                    $this->type = 'standby';
                    break;
                case 'bs':
                    $this->type = 'break supervision';
                    break;
                case 'ex':
                    $this->type = 'examination';
                    break;
            }
        }

        foreach ($data['kl'] as $value) {
            $temp = $query->get('Classes')->findBy(['id' => $value['id']]);
            if(isset($temp[0])){
                $this->classes[] = $temp[0];
            }
        }

        foreach ($data['te'] as $value) {
            $temp = $query->get('Teachers')->findBy(['id' => $value['id']]);
            if(isset($temp[0])) {
                $this->teachers[] = $temp[0];
            }
        }

        foreach ($data['su'] as $value) {
            $temp = $query->get('Subjects')->findBy(['id' => $value['id']]);
            if(isset($temp[0])){
                $this->subjects[] = $temp[0];
            }
        }
        foreach ($data['ro'] as $value) {
            $temp = $query->get('Rooms')->findBy(['id' => $value['id']]);
            if(isset($temp[0])) {
                $this->rooms[] = $temp[0];
            }
        }
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
    /**
     * sets the startTime
     * @param \DateTime $startTime
     * @return Period $this
     */
    public function setStartTime(\DateTime $startTime) {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * return the endTime
     * @return \DateTime
     */
    public function getEndTime() {
        return $this->endTime;
    }

    /**
     * sets the endTime
     * @param \DateTime $endTime
     * @return Period $this
     */
    public function setEndTime(\DateTime $endTime) {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * return the classes
     * @return Classes[]
     */
    public function getClasses() {
        return $this->classes;
    }

    /**
     * sets the classes
     * @param Classes[] $classes
     * @return Period $this
     */
    public function setClasses(array $classes) {
        $this->classes = $classes;

        return $this;
    }

    /**
     * return the teachers
     * @return Teachers[]
     */
    public function getTeachers() {
        return $this->teachers;
    }

    /**
     * sets the teachers
     * @param Teachers[] $teachers
     * @return Period $this
     */
    public function setTeachers(array $teachers) {
        $this->teachers = $teachers;

        return $this;
    }

    /**
     * returns the subjects
     * @return Subjects[]
     */
    public function getSubjects() {
        return $this->subjects;
    }

    /**
     * sets the subjects
     * @param Subjects[] $subjects
     * @return Period $this
     */
    public function setSubjects(array $subjects) {
        $this->subjects = $subjects;

        return $this;
    }

    /**
     * return the rooms
     * @return Rooms[]
     */
    public function getRooms() {
        return $this->rooms;
    }

    /**
     * sets the rooms
     * @param Rooms[] $rooms
     * @return Period $this
     */
    public function setRooms(array $rooms) {
        $this->rooms = $rooms;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCode() {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code) {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type) {
        $this->type = $type;
    }

    public function get($key) {
        switch ($key) {
            case 'teachers':
                return $this->teachers;
            case 'classes':
                return $this->classes;
            case 'rooms':
                return $this->rooms;
            case 'subjects':
                return $this->subjects;
            default:
                throw new ModelException('array of objects' . $key . 'doesn\'t exist');
        }
    }
}