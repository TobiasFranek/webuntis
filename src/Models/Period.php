<?php
declare(strict_types=1);

namespace Webuntis\Models;

use Webuntis\Exceptions\ModelException;
use Webuntis\Query\Query;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Period Model
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
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
    public function getStartTime() : \DateTime 
    {
        return $this->startTime;
    }

    /**
     * sets the startTime
     * @param \DateTime $startTime
     * @return Period
     */
    public function setStartTime(\DateTime $startTime) : self 
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * return the endTime
     * @return \DateTime
     */
    public function getEndTime() : \DateTime 
    {
        return $this->endTime;
    }

    /**
     * sets the endTime
     * @param \DateTime $endTime
     * @return Period
     */
    public function setEndTime(\DateTime $endTime) : self 
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * return the classes
     * @return Classes[]
     */
    public function getClasses() : array 
    {
        return $this->classes;
    }

    /**
     * sets the classes
     * @param Classes[] $classes
     * @return Period
     */
    public function setClasses(array $classes) : self 
    {
        $this->classes = $classes;

        return $this;
    }

    /**
     * return the teachers
     * @return Teachers[]
     */
    public function getTeachers() : array 
    {
        return $this->teachers;
    }

    /**
     * sets the teachers
     * @param Teachers[] $teachers
     * @return Period
     */
    public function setTeachers(array $teachers)  : self 
    {
        $this->teachers = $teachers;

        return $this;
    }

    /**
     * returns the subjects
     * @return Subjects[]
     */
    public function getSubjects()  : array 
    {
        return $this->subjects;
    }

    /**
     * sets the subjects
     * @param Subjects[] $subjects
     * @return Period
     */
    public function setSubjects(array $subjects) : self 
    {
        $this->subjects = $subjects;

        return $this;
    }

    /**
     * return the rooms
     * @return Rooms[]
     */
    public function getRooms() : array 
    {
        return $this->rooms;
    }

    /**
     * sets the rooms
     * @param Rooms[] $rooms
     * @return Period
     */
    public function setRooms(array $rooms) : self 
    {
        $this->rooms = $rooms;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode() : string 
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return Period
     */
    public function setCode(string $code) : self 
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function getType() : string 
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Period
     */
    public function setType(string $type) : self 
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param string $key
     * @return Classes[]|Rooms[]|Subjects[]|Teachers[]
     * @throws ModelException
     */
    public function get(string $key) : array
    {
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
     * @param string $field
     * @param mixed $value
     * @return Period
     */
    public function set(string $field, $value) : self
    {
        $this->$field = $value;

        return $this;
    }
}