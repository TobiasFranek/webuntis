<?php
declare(strict_types=1);

namespace Webuntis;

use Webuntis\Models\Interfaces\ConfigurationModelInterface;

/**
 * creates an Webuntis instance by the given context,
 * which is defined by the model or config
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class WebuntisFactory {

    /**
     * @var array
     */
    private static $config = [];

    /**
     * @var Webuntis[]
     */
    private static $instances = [];

    /**
     * WebuntisFactory constructor.
     */
    private function __construct() {
    }

    /**
     * creates the right Webuntis instance and also caches it
     * @param string $model
     * @return Webuntis
     */
    public static function create(string $model = null) : object 
    {
        if ($model) {
            $interfaces = class_implements($model);

            if (isset($interfaces[ConfigurationModelInterface::class])) {
                $config = $model::CONFIG_NAME;
            } else if (isset(self::$config['only_admin']) && self::$config['only_admin']) {
                $config = 'admin';
            } else {
                $config = 'default';
            }
        } else {
            $config = 'default';
        }


        if (!isset(self::$instances[$config])) {
            self::$instances[$config] = new Webuntis(self::$config[$config], $config);
        }
        return self::$instances[$config];
    }

    /**
     * add one config part f.e. an new instance
     * @param string $name
     * @param array $config
     */
    public static function addConfig(string $name, array $config) : void 
    {
        self::$config[$name] = $config;
    }

    /**
     * set the current config new
     * @param array $config
     */
    public static function setConfig(array $config) : void 
    {
        self::$config = $config;
    }

    /**
     * return the current configuration
     * @return array
     */
    public static function getConfig() : array 
    {
        return self::$config;
    }

}