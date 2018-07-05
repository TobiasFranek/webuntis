<?php
declare(strict_types=1);

namespace Webuntis\Models;

use Webuntis\Models\Interfaces\CachableModelInterface;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Classes Model
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class Classes extends AbstractModel implements CachableModelInterface {

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
     * @var int
     */
    const CACHE_LIFE_TIME = 86400;

    /**
     * @var string
     */
    const METHOD = 'getKlassen';

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
     * @return Classes $this
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
     * @return Classes $this
     */
    public function setFullName(string $fullName) : self
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * sets an given field
     * @param string $field
     * @param mixed $value
     * @return Classes
     */
    public function set(string $field, $value) : self 
    {
        $this->$field = $value;

        return $this;
    }
}