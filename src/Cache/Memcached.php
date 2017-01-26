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

namespace Webuntis\Cache;


use Doctrine\Common\Cache\MemcachedCache;
use Webuntis\Repositories\Repository;

/**
 * Class Memcached
 * @package Webuntis\Cache
 * @author Tobias Franek <tobias.franek@gmail.com>
 */
class Memcached extends MemcachedCache {

    /**
     * @var bool
     */
    private $disabledCache;

    public function __construct() {
        $this->disabledCache = Repository::$disabledCache;
    }

    /**
     * {@inheritdoc}
     */
    public function contains($id) {
        if ($this->disabledCache == false) {
            return parent::contains($id);
        } else {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete($id) {
        if ($this->disabledCache == false) {
            return parent::delete($id);
        } else {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function deleteAll() {
        if ($this->disabledCache == false) {
            return parent::deleteAll();
        } else {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function fetch($id) {
        if ($this->disabledCache == false) {
            return parent::fetch($id);
        } else {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function fetchMultiple(array $keys) {
        if ($this->disabledCache == false) {
            return parent::fetchMultiple($keys);
        } else {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function flushAll() {
        if ($this->disabledCache == false) {
            return parent::flushAll();
        } else {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function save($id, $data, $lifeTime = 0) {
        if ($this->disabledCache == false) {
            return parent::save($id, $data, $lifeTime);
        } else {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function saveMultiple(array $keysAndValues, $lifetime = 0) {
        if ($this->disabledCache == false) {
            return parent::saveMultiple($keysAndValues, $lifetime);
        } else {
            return false;
        }
    }
}