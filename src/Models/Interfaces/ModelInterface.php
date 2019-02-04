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
     * @return self
     */
    public function setId($id) : self;

    /**
     * @param string $format
     * @return array|string
     */
    public function serialize(?string $format);

    /**
     * @param array $attributes
     * @return self
     */
    public function setAttributes(array $attributes) : self;

    /**
     * @return array
     */
    public function getAttributes(): array;
}