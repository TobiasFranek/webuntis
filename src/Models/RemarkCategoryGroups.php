<?php
declare(strict_types=1);

namespace Webuntis\Models;

use Webuntis\Models\Interfaces\CachableModelInterface;
/**
 * Classes Model
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class RemarkCategoryGroups extends AbstractModel implements CachableModelInterface {

    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    const CACHE_LIFE_TIME = 86400;

    /**
     * @var string
     */
    const METHOD = 'getClassregCategoryGroups';

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
     * @return RemarkCategoryGroups
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
     * @return RemarkCategoryGroups
     */
    public function set(string $field, $value) : self 
    {
        $this->$field = $value;

        return $this;
    }
}