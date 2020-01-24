<?php
declare(strict_types=1);

namespace Webuntis\Models;

use Webuntis\Models\AbstractModel;
use Webuntis\Models\Interfaces\AdministrativeModelInterface;

/**
 * Period Model
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class LastImportTime extends AbstractModel implements AdministrativeModelInterface
{
    const METHOD = "getLatestImportTime";

    /**
     * @var int
     */
    private $time;

    /**
     * Getter for time
     *
     * @return int
     */
    public function getTime() : int
    {
        return $this->time;
    }

    /**
     * Setter for time
     *
     * @param int $time
     */
    public function setTime(int $time) : self
    {
        $this->time = $time;

        return $this;
    }

    /**
     * sets an given field
     *
     * @param mixed $field
     * @param mixed $value
     */
    public function set($field, $value)
    {
        $this->$field = $value;
    }
}
