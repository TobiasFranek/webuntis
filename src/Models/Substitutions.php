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
 * Class Substitutions
 * @package Webuntis\Models
 * @author Tobias Franek <tobias.franek@gmail.com>
 */
class Substitutions extends AbstractModel {

    /**
     * @var string
     */
    private $type;

    /**
     * @var Period
     */
    private $lesson;

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
    private $text;

    /**
     * @var string
     */
    const METHOD = 'getSubstitutions';

    /**
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @return Classes[]
     */
    public function getClasses() {
        return $this->classes;
    }

    /**
     * @return \DateTime
     */
    public function getEndTime() {
        return $this->endTime;
    }

    /**
     * @return Period
     */
    public function getLesson() {
        return $this->lesson;
    }

    /**
     * @return Rooms[]
     */
    public function getRooms() {
        return $this->rooms;
    }

    /**
     * @return \DateTime
     */
    public function getStartTime() {
        return $this->startTime;
    }

    /**
     * @return Subjects[]
     */
    public function getSubjects() {
        return $this->subjects;
    }

    /**
     * @return Teachers[]
     */
    public function getTeachers() {
        return $this->teachers;
    }

    /**
     * @return string
     */
    public function getText() {
        return $this->text;
    }

    /**
     * @param string $type
     */
    public function setType($type) {
        $this->type = $type;
    }

    /**
     * @param Classes[] $classes
     */
    public function setClasses($classes) {
        $this->classes = $classes;
    }

    /**
     * @param \DateTime $endTime
     */
    public function setEndTime($endTime) {
        $this->endTime = $endTime;
    }

    /**
     * @param Period $lesson
     */
    public function setLesson($lesson) {
        $this->lesson = $lesson;
    }

    /**
     * @param Rooms[] $rooms
     */
    public function setRooms($rooms) {
        $this->rooms = $rooms;
    }

    /**
     * @param \DateTime $startTime
     */
    public function setStartTime($startTime) {
        $this->startTime = $startTime;
    }

    /**
     * @param Subjects[] $subjects
     */
    public function setSubjects($subjects) {
        $this->subjects = $subjects;
    }

    /**
     * @param Teachers[] $teachers
     */
    public function setTeachers($teachers) {
        $this->teachers = $teachers;
    }

    /**
     * @param string $text
     */
    public function setText($text) {
        $this->text = $text;
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