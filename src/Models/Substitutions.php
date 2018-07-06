<?php
declare(strict_types=1);

namespace Webuntis\Models;


use Webuntis\Exceptions\ModelException;
use Webuntis\Query\Query;
use Webuntis\Models\Period;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Substitutions Model
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
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
    public function getType() : string 
    {
        return $this->type;
    }

    /**
     * @return Classes[]
     */
    public function getClasses() : array 
    {
        return $this->classes;
    }

    /**
     * @return \DateTime
     */
    public function getEndTime() : \DateTime 
    {
        return $this->endTime;
    }

    /**
     * @return Period
     */
    public function getLesson() : Period 
    {
        return $this->lesson;
    }

    /**
     * @return Rooms[]
     */
    public function getRooms() : array 
    {
        return $this->rooms;
    }

    /**
     * @return \DateTime
     */
    public function getStartTime() : \DateTime 
    {
        return $this->startTime;
    }

    /**
     * @return Subjects[]
     */
    public function getSubjects() : array 
    {
        return $this->subjects;
    }

    /**
     * @return Teachers[]
     */
    public function getTeachers() : array 
    {
        return $this->teachers;
    }

    /**
     * @return string
     */
    public function getText() : string 
    {
        return $this->text;
    }

    /**
     * @param string $type
     * @return Substitutions
     */
    public function setType(string $type) : self 
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param Classes[] $classes
     * @return Substitutions
     */
    public function setClasses(array $classes) :self 
    {
        $this->classes = $classes;

        return $this;
    }

    /**
     * @param \DateTime $endTime
     * @return Substitutions
     */
    public function setEndTime(\DateTime $endTime) : self 
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * @param Period $lesson
     * @return Substitutions
     */
    public function setLesson(Period $lesson) : self 
    {
        $this->lesson = $lesson;

        return $this;
    }

    /**
     * @param Rooms[] $rooms
     * @return Substitutions
     */
    public function setRooms(array $rooms) : self {
        $this->rooms = $rooms;

        return $this;
    }

    /**
     * @param \DateTime $startTime
     * @return Substitutions
     */
    public function setStartTime(\DateTime $startTime) : self
    {
        $this->startTime = $startTime;
    
        return $this;
    }

    /**
     * @param Subjects[] $subjects
     * @return Substitutions
     */
    public function setSubjects(array $subjects) : self
    {
        $this->subjects = $subjects;

        return $this;
    }

    /**
     * @param Teachers[] $teachers
     * @return Substitutions
     */
    public function setTeachers(array $teachers) : self 
    {
        $this->teachers = $teachers;

        return $this;
    }

    /**
     * @param string $text
     * @return Substitutions
     */
    public function setText(string $text) : self 
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @param string $key
     * @return Classes[]|Rooms[]|Subjects[]|Teachers[]
     * @throws ModelException
     */
    public function get(string $key) : array {
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
     * @return Substitutions
     */
    public function set(string $field, $value) : self 
    {
        $this->$field = $value;

        return $this;
    }
}