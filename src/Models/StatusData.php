<?php

namespace Webuntis\Models;

use Webuntis\Models\AbstractModel;
use Webuntis\Models\Interfaces\CachableModelInterface;
use Webuntis\Models\Interfaces\AdministrativeModelInterface;
use JMS\Serializer\Annotation\SerializedName;

class StatusData extends AbstractModel implements CachableModelInterface, AdministrativeModelInterface
{
    const CACHE_LIFE_TIME = 0;
    const METHOD = "getStatusData";

    /**
     * @var array
     * @SerializedName("lessonTypes")
     */
    private $lessonTypes;

    /**
     * @var array
     */
    private $codes;

    /**
     * Getter for lessonTypes
     *
     * @return array
     */
    public function getLessonTypes() : array
    {
        return $this->lessonTypes;
    }

    /**
     * Setter for lessonTypes
     *
     * @param array $lessonTypes
     * 
     * @return StatusData
     */
    public function setLessonTypes(array $lessonTypes) : self
    {
        $this->lessonTypes = $lessonTypes;
        
        return $this;
    }

    /**
     * Getter for codes
     *
     * @return array
     */
    public function getCodes() : array
    {
        return $this->codes;
    }

    /**
     * Setter for codes
     *
     * @param array $codes
     * 
     * @return StatusData
     */
    public function setCodes(array $codes) : self
    {
        $this->codes = $codes;

        return $this;
    }

    /**
     * sets an given field
     *
     * @param mixed $field
     * @param mixed $value
     * 
     * @return StatusData
     */
    public function set($field, $value) : self
    {
        $this->$field = $value;

        return $this;
    }
}
