<?php
declare(strict_types=1);

namespace Webuntis\Models;

/**
 * RemarkCategories Model
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class RemarkCategories extends Classes {
    /**
     * @var string
     */
    const METHOD = 'getClassregCategories';

    /**
     * @var mixed
     */
    protected $group;

    /**
     * sets group
     * @param mixed
     * @return RemarkCategories
     */
    public function setGroup($group) : self
    {
        $this->group = $group;

        return $this;
    }

    /**
     * return the group
     * @return mixed
     */
    public function getGroup()
    {
        return $this->group;
    }
}