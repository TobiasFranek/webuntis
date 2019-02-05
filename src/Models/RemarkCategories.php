<?php
declare(strict_types=1);

namespace Webuntis\Models;

use Webuntis\Models\Interfaces\ModelInterfaces;

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
     * @var ModelInterface
     */
    protected $group;

    /**
     * sets group
     * @param mixed
     * @return ModelInterface
     */
    public function setGroup($group) : ModelInterface
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