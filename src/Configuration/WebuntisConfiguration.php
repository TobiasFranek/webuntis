<?php
declare(strict_types=1);

namespace Webuntis\Configuration;

use Webuntis\Repositories\Repository;
use Webuntis\WebuntisFactory;
use Webuntis\Models\AbstractModel;
use Webuntis\Models\Interfaces\ConfigurationModelInterface;

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
     * gets the right instance config from a given model
     * @param AbstractModel $model 
     * @return array
     */
    public static function getConfigByModel(AbstractModel $model) : array
    {
        $config = WebuntisFactory::getConfig();
        $model = get_class($model);
        $interfaces = class_implements($model);
        if (isset($interfaces[ConfigurationModelInterface::class])) {
            $configName = $model::CONFIG_NAME;
        } else if (isset($config['only_admin']) && $config['only_admin']) {
            $configName = 'admin';
        } else {
            $configName = 'default';
        }

        return $config[$configName];
    } 

    /**
     * returns the name of the configuration from a given model
     * @param AbstractModel $model 
     * @return string
     */
    public static function getConfigNameByModel(AbstractModel $model) : string 
    {
        $config = WebuntisFactory::getConfig();
        $model = get_class($model);
        $interfaces = class_implements($model);
        if (isset($interfaces[ConfigurationModelInterface::class])) {
            $configName = $model::CONFIG_NAME;
        } else if (isset($config['only_admin']) && $config['only_admin']) {
            $configName = 'admin';
        } else {
            $configName = 'default';
        }

        return $configName;
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