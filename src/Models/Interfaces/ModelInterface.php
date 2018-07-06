<?php
declare(strict_types=1);

namespace Webuntis\Models\Interfaces;

use Webuntis\Models\AbstractModel;

/**
 * declares the minimum fields an Models must have
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
interface ModelInterface {
    /**
     * @return int|string
     */
    public function getId();

    /**
     * @param int|string $id
     * @return AbstractModel
     */
    public function setId($id) : AbstractModel;

    /**
     * @param string $format
     * @return array|string
     */
    public function serialize(?string $format);
}