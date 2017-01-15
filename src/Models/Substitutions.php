<?php

namespace Webuntis\Models;


use Webuntis\Exceptions\ModelException;
use Webuntis\Query\Query;

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
    private $text;


    function serialize() {
        return [
            'type' => $this->type,
            'text' => $this->text,
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
            'lesson' => $this->lesson,
            'classes' => $this->serializeObj($this->classes),
            'teachers' => $this->serializeObj($this->teachers),
            'subjects' => $this->serializeObj($this->subjects),
            'rooms' => $this->serializeObj($this->rooms)
        ];
    }
    protected function parse($data) {
        $query = new Query();
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
        if(isset($data['type'])) {
            $this->type = $data['type'];
        }
        if(isset($data['text'])) {
            $this->type = $data['text'];
        }
        if(isset($data['lsid'])) {
            $this->lesson = $data['lsid'];
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