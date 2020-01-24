<?php
declare(strict_types=1);

namespace Webuntis\Models;

use Webuntis\Models\Interfaces\AdministrativeModelInterface;
use Webuntis\Models\Interfaces\CachableModelInterface;
use JMS\Serializer\Annotation\SerializedName;


/**
 * Teachers Model
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class Teachers extends AbstractModel implements AdministrativeModelInterface, CachableModelInterface {
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
     * @var int
     */
    const CACHE_LIFE_TIME = 86400;

    /**
     * @var string
     */
    const METHOD = "getTeachers";

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
     * @return Teachers
     */
    public function setName(string $name) : self 
    {
        $this->name = $name;

        return $this;
    }

    /**
     * returns the firstName
     * @return string
     */
    public function getFirstName() : string 
    {
        return $this->firstName;
    }

    /**
     * sets the firstName
     * @param string $firstName
     * @return Teachers
     */
    public function setFirstName(string $firstName) : self 
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * return the lastName
     * @return string
     */
    public function getLastName() : string 
    {
        return $this->lastName;
    }

    /**
     * sets the lastName
     * @param string $lastName
     * @return Teachers
     */
    public function setLastName(string $lastName) : self 
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * sets an given field
     * @param string $field
     * @param mixed $value
     * @return Teachers
     */
    public function set(string $field, $value) : self 
    {
        $this->$field = $value;

        return $this;
    }
}