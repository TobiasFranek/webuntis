<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

namespace Webuntis;


use Webuntis\Models\Interfaces\ConfigurationModelInterface;

/**
 * Class WebuntisFactory
 * @package Webuntis
 * @author Tobias Franek <tobias.franek@gmail.com>
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
    public static function create($model = null) {
        if($model !=  null) {
            $interfaces = class_implements($model);

            if (isset($interfaces[ConfigurationModelInterface::class])) {
                $config = $model::CONFIG_NAME;
            } else if (isset(static::$config['only_admin']) && static::$config['only_admin']){
                $config = 'admin';
            } else {
                $config = 'default';
            }
        }else {
            $config = 'default';
        }


        if (!isset(static::$instances[$config])) {
            static::$instances[$config] = new Webuntis(static::$config[$config]);
        }
        return static::$instances[$config];
    }

    /**
     * add one config part f.e. an new instance
     * @param string $name
     * @param array $config
     */
    public static function addConfig($name, array $config) {
        static::$config[$name] = $config;
    }

    /**
     * set the current config new
     * @param array $config
     */
    public static function setConfig(array $config) {
        static::$config = $config;
    }

    /**
     * return the current configuration
     * @return array
     */
    public static function getConfig() {
        return static::$config;
    }

}