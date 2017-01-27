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

namespace Webuntis\Query;

use Webuntis\Configuration\YAMLConfiguration;
use Webuntis\Exceptions\QueryException;
use Webuntis\Repositories\Repository;

/**
 * Class Query
 * @package Webuntis\Query
 * @author Tobias Franek <tobias.franek@gmail.com>
 */
class Query {

    /**
     * @var Repository[]
     */
    private static $cachedRepositories = [];

    /**
     * Query constructor.
     */
    public function __construct() {
        $config = new YAMLConfiguration();

        $this->models = $config->getModels();
        $this->repositories = $config->getRepositories();
    }

    /**
     * gets the right repository to the right model
     * @param string $className
     * @return Repository
     */
    public function get($className) {
        if($className == 'User') {
            if (!isset(static::$cachedRepositories[$className])) {
                static::$cachedRepositories[$className] = new $this->repositories[$className]();
            }
            return static::$cachedRepositories[$className];
        }
        if (isset($this->models[$className])) {
            if (isset($this->repositories[$className])) {
                $name = $className;
            } else {
                $name = 'Default';
            }
            if (!isset(static::$cachedRepositories[$className])) {
                static::$cachedRepositories[$className] = new $this->repositories[$name]($this->models[$className]);
            }
            return static::$cachedRepositories[$className];
        }
        throw new QueryException('Model ' . $className . ' not found');
    }
}