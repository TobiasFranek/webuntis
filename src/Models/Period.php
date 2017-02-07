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
use JMS\Serializer\Annotation\SerializedName;

/**
 * Class Period
 * @package Webuntis\Models
 * @author Tobias Franek <tobias.franek@gmail.com>
 */
class Period extends AbstractModel {

    /**
     * @SerializedName("startTime")
     * @var \DateTime
     */
    private $startTime;

    /**
     * @SerializedName("endTime")
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

    /**
     * @param $key
     * @return Classes[]|Rooms[]|Subjects[]|Teachers[]
     */
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

    /**
     * sets an given field
     * @param $field
     * @param $value
     */
    public function set($field, $value) {
        $this->$field = $value;
    }
}