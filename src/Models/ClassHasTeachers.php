<?php
declare(strict_types=1);

namespace Webuntis\Models;


use Webuntis\Exceptions\ModelException;
use Webuntis\Query\Query;
use JMS\Serializer\Annotation\SerializedName;

/**
 * ClassHasTeachers Model
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class ClassHasTeachers extends AbstractModel {
    /**
     * @var string
     */
    private $name;

    /**
     * @SerializedName("fullName")
     * @var string
     */
    private $fullName;
    /**
     * @var Teachers[]
     */
    private $teachers = [];

    /**
     * returns the Teachers
     * @return Teachers[]
     */
    public function getTeachers() : array{
        return $this->teachers;
    }

    /**
     * sets the Teachers
     * @param Teachers[] $teachers
     * @return $this
     */
    public function setTeachers(array $teachers) : self 
    {
        $this->teachers = $teachers;

        return $this;
    }
    /**
     * returns the name
     * @return string
     */
    public function getName() : string 
    {
        return $this->name;
    }

    /**
     * set the name
     * @param string $name
     * @return ClassHasTeachers $this
     */
    public function setName(string $name) : self 
    {
        $this->name = $name;

        return $this;
    }

    /**
     * returns the fullName
     * @return string
     */
    public function getFullName() : string 
     {
        return $this->fullName;
    }

    /**
     * set the fullName
     * @param string $fullName
     * @return ClassHasTeachers $this
     */
    public function setFullName(string $fullName) : self 
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * return the children by given id
     * @param $key
     * @return AbstractModel[]
     */
    public function get(string $key) : array
    {
        switch ($key) {
            case 'teachers':
                return $this->teachers;
            default:
                throw new ModelException('array of objects' . $key . 'doesn\'t exist');
        }
    }
    /**
     * sets an given field
     * @param string $field
     * @param mixed $value
     * @return ClassHasTeachers
     */
    public function set(string $field, $value) : self 
    {
        $this->$field = $value;

        return $this;
    }
}