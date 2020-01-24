<?php
declare(strict_types=1);

namespace Webuntis\Models;

use Webuntis\Configuration\YAMLConfiguration;
use Webuntis\Models\Interfaces\AdministrativeModelInterface;
use Webuntis\Models\Interfaces\CachableModelInterface;
use Webuntis\Types\StringType;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Students Model
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class Students extends AbstractModel implements AdministrativeModelInterface, CachableModelInterface {
    /**
     * @var string
     */
    private $name;

    /**
     * @SerializedName("firstName")
     * @var string
     */
    private $firstName;

    /**
     * @SerializedName("lastName")
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $gender;

    /***
     * @var int
     */
    const CACHE_LIFE_TIME = 86400;

    /**
     * @var string
     */
    const METHOD = "getStudents";

    /**
     * return the name
     * @return string
     */
    public function getName() : string 
    {
        return $this->name;
    }

    /**
     * sets the name
     * @param string $name
     * @return Students
     */
    public function setName(string $name) : self 
    {
        $this->name = $name;

        return $this;
    }

    /**
     * return the firstName
     * @return string
     */
    public function getFirstName() : string 
    {
        return $this->firstName;
    }

    /**
     * set the firstName
     * @param string $firstName
     * @return Students
     */
    public function setFirstName(string $firstName) : self 
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * get the lastName
     * @return string
     */
    public function getLastName() : string 
    {
        return $this->lastName;
    }

    /**
     * sets the lastName
     * @param string $lastName
     * @return Students 
     */
    public function setLastName(string $lastName) : self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * returns the gender
     * @return string
     */
    public function getGender() : string 
    {
        return $this->gender;
    }

    /**
     * sets the gender
     * @param string $gender
     * @return Students
     */
    public function setGender(string $gender) : self 
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * returns the key
     * @return string
     */
    public function getKey() : string 
    {
        return $this->key;
    }

    /**
     * sets the key
     * @param string $key
     * @return Students
     */
    public function setKey(string $key) : self 
    {
        $this->key = $key;

        return $this;
    }

    /**
     * sets an given field
     * @param string $field
     * @param mixed $value
     * @return Students
     */
    public function set(string $field, $value) : self {
        $this->$field = $value;

        return $this;
    }
}