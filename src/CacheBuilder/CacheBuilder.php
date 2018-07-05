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

namespace Webuntis\CacheBuilder;

use Webuntis\CacheBuilder\Routines\MemcacheRoutine;
use Webuntis\Configuration\WebuntisConfiguration;

/**
 * Class Memcached
 * @package Webuntis\CacheBuilder
 * @author Tobias Franek <tobias.franek@gmail.com>
 */
class CacheBuilder  {

    /**
     * @var object[]
     */
    private static $caches = [];

    /**
     * @var string
     */
    const DEFAULT = 'memcached';

    /**
     * @var array
     */
    private $routines = [
        'memcached' => MemcacheRoutine::class
    ]; 

    /**
     * @var string
     */
    private $cacheType;

    /**
     * @var array
     */
    private $config = [];

    /**
     * @var boolean
     */
    private $cacheDisabled = false;

    public function __construct() {
        $config = WebuntisConfiguration::getConfig();
        $this->cacheType = self::DEFAULT;

        if(isset($config['disable_cache'])) {
            $this->cacheDisabled = $config['disable_cache'];
        }
        if(isset($config['cache'])) {
            if(isset($config['cache']['routines'])) {
                $this->routines = array_merge($config['cache']['routines'], $this->routines);
            }
            if(isset($config['cache']['type'])) {
                $this->cacheType = $config['cache']['type'];
            }
            $this->config = $config['cache'];
        }

    }

    /**
     * creates an Cache Instance
     * @return object|bool
     */
    public function create() {
        if(!$this->cacheDisabled) {
            if (!isset(self::$caches[$this->cacheType])) {
                self::$caches[$this->cacheType] = $this->routines[$this->cacheType]::execute($this->config);
            } 
            return self::$caches[$this->cacheType];
        } else {
            return false;
        }
    }
}