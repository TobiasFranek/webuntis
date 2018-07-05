<?php
declare(strict_types=1);

namespace Webuntis\Models\Interfaces;

/**
 * AdministrativeModelInterface is just for the access control all model that implements this will be executed in the 'admin' context
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
interface AdministrativeModelInterface extends ConfigurationModelInterface {
    /**
     * @var string
     */
    const CONFIG_NAME = 'admin';
}