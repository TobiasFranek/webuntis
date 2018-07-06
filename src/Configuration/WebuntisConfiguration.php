<?php
declare(strict_types=1);

namespace Webuntis\Configuration;

use Webuntis\Repositories\Repository;
use Webuntis\WebuntisFactory;

/**
 * manages the different configurations and passes them to the WebuntisFactory
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class WebuntisConfiguration {

    /**
     * WebuntisConfiguration constructor.
     * @param array $config
     */
    public function __construct(array $config) {
        self::setConfig($config);
    }

    /**
     * adds config f.e. another instance to the WebuntisFactry
     * @param array $config
     */
    public static function addConfig(array $config) : void
    {
        $newConfig = array_merge(self::getConfig(), $config);

        WebuntisFactory::setConfig($newConfig);
    }

    /**
     * gets the current config
     * @return array
     */
    public static function getConfig() : array
    {
        return WebuntisFactory::getConfig();
    }

    /**
     * sets the current config in the WebuntisFactory
     * @param array $config
     * @return WebuntisConfiguration $this
     */
    public static function setConfig(array $config) : void
    {
        WebuntisFactory::setConfig($config);
    }
}