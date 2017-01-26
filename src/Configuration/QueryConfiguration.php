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

namespace Webuntis\Configuration;


use Symfony\Component\Yaml\Yaml;
use Webuntis\Repositories\Repository;
use Webuntis\Repositories\UserRepository;

class QueryConfiguration {

    /**
     * @var array
     */
    private static $files = [];

    /**
     * @var array
     */
    private $models = [];

    /**
     * @var array
     */
    private $repositories = [
        'Default' => Repository::class,
        'User' => UserRepository::class,
    ];

    public function __construct() {
        if(empty(self::$files)) {
            $this->load();
        }
        $this->parse();
    }

    /**
     * parses the files to the repos and models
     */
    public function parse() {
        foreach(self::$files as $value) {
            $parsedFile = YAML::parse(file_get_contents($value));
            $namespace = array_keys($parsedFile)[0];
            $splittedNamespace = explode("\\", $namespace);
            $modelName = $splittedNamespace[count($splittedNamespace) - 1];
            if($parsedFile[$namespace]['repositoryClass'] != null) {
                $this->repositories[$modelName] = $parsedFile[$namespace]['repositoryClass'];
            }
            $this->models[$modelName] = $namespace;
        }
    }

    /**
     * @return array
     */
    public function getModels() {
        return $this->models;
    }

    /**
     * @return array
     */
    public function getRepositories() {
        return $this->repositories;
    }

    /**
     * loads all webuntis.yml config files
     */
    public function load() {
        self::$files = $this->rglob('*.webuntis.yml');
    }

    /**
     * recursive glob method
     * @param $pattern
     * @param int $flags
     * @return array
     */
    private function rglob($pattern, $flags = 0) {
        $files = glob($pattern, $flags);
        foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir) {
            $files = array_merge($files, $this->rglob($dir.'/'.basename($pattern), $flags));
        }
        return $files;
    }
}