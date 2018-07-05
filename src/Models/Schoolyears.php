<?php
declare(strict_types=1);

namespace Webuntis\Models;

use Webuntis\Models\Interfaces\CachableModelInterface;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Schoolyears Model
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class Schoolyears extends AbstractModel implements CachableModelInterface {
    /**
     * @var string
     */
    private $name;

    /**
     * @SerializedName("startDate")
     * @var \DateTime
     */
    private $startDate;

    /**
     * @SerializedName("endDate")
     * @var \DateTime
     */
    private $endDate;

    /**
     * @var int
     */
    const CACHE_LIFE_TIME = 86400;

    /**
     * @var string
     */
    const METHOD = 'getSchoolyears';

    /**
     * sets name
     * @param string $name
     * @return Schoolyear
     */
    public function setName(string $name) : self 
    {
        $this->name = $name;

        return $this;
    }

    /**
     * return the name
     * @return string
     */
    public function getName() : string 
    {
        return $this->name;
    }

    /**
     * set the startDate
     * @param \DateTime $startDate
     * @return Schoolyear
     */
    public function setStartDate(\DateTime $startDate) : self 
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * return the startDate
     * @return \DateTime
     */
    public function getStartDate() : \DateTime 
    {
        return $this->startDate;
    }

    /**
     * sets the endDate
     * @param \DateTime $endDate
     * @return Schoolyear
     */
    public function setEndDate(\DateTime $endDate) : self
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * returns the endDate
     * @return \DateTime
     */
    public function getEndDate() : \DateTime 
    {
        return $this->endDate;
    }

    /**
     * sets an given field
     * @param string $field
     * @param mixed $value
     * @return Schoolyear
     */
    public function set(string $field, $value) : self 
    {
        $this->$field = $value;

        return $this;
    }
}