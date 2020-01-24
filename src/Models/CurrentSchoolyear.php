<?php
declare(strict_types=1);

namespace Webuntis\Models;

use Webuntis\Models\Interfaces\CachableModelInterface;
use Webuntis\Models\Schoolyears;

/**
 * CurrentSchoolyear Model
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class CurrentSchoolyear extends Schoolyears implements CachableModelInterface {

    /**
     * @var int
     */
    const CACHE_LIFE_TIME = 86400;

    /**
     * @var string
     */
    const METHOD = 'getCurrentSchoolyear';
}