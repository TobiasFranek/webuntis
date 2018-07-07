<?php
declare(strict_types=1);

namespace Webuntis\Models;

use Webuntis\Models\AbstractModel;
use Webuntis\Models\Interfaces\CachableModelInterface;
use Webuntis\Models\Interfaces\AdministrativeModelInterface;
use JMS\Serializer\Annotation\SerializedName;

/**
 * TimegridUntits Model
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class TimegridUnits extends AbstractModel implements CachableModelInterface, AdministrativeModelInterface
{
    const CACHE_LIFE_TIME = 0;
    const METHOD = "getTimegridUnits";

    /**
     * @var int
     */
    private $day;

    /**
     * @var array
     * @SerializedName("timeUnits")
     */
    private $timeUnits;

    /**
     * Getter for day
     *
     * @return int
     */
    public function getDay() : int
    {
        return $this->day;
    }

    /**
     * Setter for day
     *
     * @param int $day
     * 
     * @return TimegridUnits
     */
    public function setDay(int $day) : self
    {
        $this->day = $day;

        return $this;
    }

    /**
     * Getter for timeUnits
     *
     * @return array
     */
    public function getTimeUnits() : array
    {
        return $this->timeUnits;
    }

    /**
     * Setter for timeUnits
     *
     * @param array $timeUnits
     * 
     * @return TimegridUnits
     */
    public function setTimeUnits(array $timeUnits) : self
    {
        $this->timeUnits = $timeUnits;

        return $this;
    }

    /**
     * sets an given field
     *
     * @param mixed $field
     * @param mixed $value
     * 
     * @return TimegridUnits
     */
    public function set($field, $value) : self
    {
        $this->$field = $value;

        return $this;
    }
}
