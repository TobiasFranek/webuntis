<?php
declare(strict_types=1);

namespace Webuntis\Models;


use Webuntis\Models\Interfaces\AdministrativeModelInterface;
use Webuntis\Models\Interfaces\CachableModelInterface;
use JMS\Serializer\Annotation\SerializedName;

/**
 * ExamTypes Model
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class ExamTypes extends AbstractModel implements CachableModelInterface, AdministrativeModelInterface {

    /**
     * @var int
     */
    private $type;

    /**
     * @var string
     */
    private $name;

    /**
     * @SerializedName("longName")
     * @var string
     */
    private $longName;

    /**
     * @var string
     */
    const METHOD = 'getExamTypes';

    /**
     * @var int
     */
    const CACHE_LIFE_TIME = 0;

    /**
     * @return int
     */
    public function getType() : int 
    {
        return $this->type;
    }

    /**
     * @param int $type
     * @return ExamTypes
     */
    public function setType(int $type) : self 
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getLongName() : string
    {
        return $this->longName;
    }

    /**
     * @return string
     */
    public function getName() : string 
    {
        return $this->name;
    }

    /**
     * @param string $longName
     * @return ExamTypes
     */
    public function setLongName(string $longName) : self 
    {
        $this->longName = $longName;

        return $this;
    }

    /**
     * @param string $name
     * @return ExamTypes
     */
    public function setName(string $name) : self
    {
        $this->name = $name;

        return $this;
    }
    /**
     * sets an given field
     * @param string $field
     * @param mixed $value
     * @return ExamTypes
     */
    public function set(string $field, $value) : self 
    {
        $this->$field = $value;

        return $this;
    }
}