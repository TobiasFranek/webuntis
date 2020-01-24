<?php
declare(strict_types=1);

namespace Webuntis\Models;

use Webuntis\Models\Interfaces\CachableModelInterface;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Holidays Model
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class Holidays extends AbstractModel implements CachableModelInterface {

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
    const METHOD = 'getHolidays';

    /**
     * returns the name
     * @return string
     */
    public function getName() : string 
    {
        return $this->name;
    }

    /**
     * sets the name
     * @param string $name
     * @return Holidays
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
     * @return Holidays
     */
    public function setFullName(string $fullName) : self 
    {
        $this->fullName = $fullName;

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
     * sets the startDate
     * @param \DateTime $date
     * @return Holidays
     */
    public function setStartDate(\DateTime $date) : self 
    {
        $this->startDate = $date;

        return $this;
    }

    /**
     * return the endDate
     * @return \DateTime
     */
    public function getEndDate() : \DateTime 
    {
        return $this->endDate;
    }

    /**
     * sets the endDate
     * @param \DateTime $date
     * @return Holidays
     */
    public function setEndDate(\DateTime $date) : self 
    {
        $this->endDate = $date;

        return $this;
    }
    /**
     * sets an given field
     * @param string $field
     * @param mixed $value
     * @return Holidays
     */
    public function set(string $field, $value) : self
    {
        $this->$field = $value;

        return $this;
    }
}