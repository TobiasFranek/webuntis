<?php
declare(strict_types=1);

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

namespace Webuntis\Configuration;


use Symfony\Component\Yaml\Yaml;
use Webuntis\Repositories\Repository;
use Webuntis\Repositories\UserRepository;

/**
 * Class QueryConfiguration
 * @package Webuntis\Configuration
 * @author Tobias Franek <tobias.franek@gmail.com>
 */
class YAMLConfiguration {

    /**
     * @var array
     */
    private static $files = [];

    /**
     * @var array
     */
    private $models = [];

    /**
     * @var string
     */
    private $vendorDir = 'vendor';

    /**
     * @var array
     */
    private $repositories = [
        'Default' => Repository::class,
        'User' => UserRepository::class,
    ];

    public function __construct() {
        
        $config = WebuntisConfiguration::getConfig();

        if(isset($config['vendorDir'])) {
            $this->vendorDir = $config['vendorDir'];
        }
        if(empty(self::$files)) {
            $this->load();
        }
        $this->parse();
    }

    /**
     * parses the files to the repos and models
     */
    private function parse() : void
    {
        foreach(self::$files as $value) {
            $namespace = array_keys($value)[0];
            $splittedNamespace = explode("\\", $namespace);
            $modelName = $splittedNamespace[count($splittedNamespace) - 1];
            if($value[$namespace]['repositoryClass'] != null) {
                $this->repositories[$modelName] = $value[$namespace]['repositoryClass'];
            }
            $this->models[$modelName] = $namespace;
        }
    }

    /**
     * @param string $namespace
     * @return array
     */
    public static function getFields(string $namespace) : array
    {
        foreach(self::$files as $value) {
            if(isset($value[$namespace])) {
                return $value[$namespace]['fields'];
            }
        }
        return [];
    }

    /**
     * returns all config fields
     * @return array
     */
    public static function getAllFields() : array
    {
        $result = [];
        foreach(self::$files as $value) {
            $result[] = $value['fields'];
        }
        return $result;
    }

    /**
     * return all the additional Types that could be defined
     * @return array
     */
    public static function getAdditionalTypes() : array
    {
        $result = [];
        foreach(self::$files as $value) {
            if(isset($value[array_keys($value)[0]]['additionalTypes'])) {
                $result = array_merge($result, $value[array_keys($value)[0]]['additionalTypes']);
            }
        }
        return $result;
    }

    /**
     * @return array
     */
    public function getModels() : array
    {
        return $this->models;
    }

    /**
     * @return array
     */
    public function getRepositories() : array
    {
        return $this->repositories;
    }

    /**
     * loads all webuntis.yml config files
     */
    public function load() : void
    {
        $explodedPath = explode('/', __DIR__);
        if(in_array($this->vendorDir, $explodedPath)) {
            $path = '';
            foreach($explodedPath as $value) {
                if($value == $this->vendorDir) {
                    break;
                }
                $path .= $value . '/';
            }
            self::$files = $this->rglob($path . '*.webuntis.yml');
        } else {
            self::$files = $this->rglob('*.webuntis.yml');
        }

        $parsedFiles = [];
        foreach(self::$files as $value) {
            $parsedFiles[] = Yaml::parse(file_get_contents($value));
        }
        self::$files = $parsedFiles;
    }

    /**
     * recursive glob method
     * @param string $pattern
     * @param int $flags
     * @return array
     */
    private function rglob(string $pattern, int $flags = 0) : array
    {
        $files = glob($pattern, $flags);
        foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir) {
            $files = array_merge($files, $this->rglob($dir.'/'.basename($pattern), $flags));
        }
        return $files;
    }
}