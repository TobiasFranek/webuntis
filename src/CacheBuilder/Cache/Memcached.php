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

namespace Webuntis\CacheBuilder\Cache;


use Doctrine\Common\Cache\MemcachedCache;
use Webuntis\Repositories\Repository;

/**
 * Class Memcached
 * @package Webuntis\Cache
 * @author Tobias Franek <tobias.franek@gmail.com>
 */
class Memcached extends MemcachedCache {

    /**
     * {@inheritdoc}
     */
    public function contains($id) {
        return parent::contains($id);
    }

    /**
     * {@inheritdoc}
     */
    public function delete($id) {
        return parent::delete($id);
    }

    /**
     * {@inheritdoc}
     */
    public function deleteAll() {
        return parent::deleteAll();
    }

    /**
     * {@inheritdoc}
     */
    public function fetch($id) {
        return parent::fetch($id);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchMultiple(array $keys) {
        return parent::fetchMultiple($keys);
    }

    /**
     * {@inheritdoc}
     */
    public function flushAll() {
        return parent::flushAll();
    }

    /**
     * {@inheritdoc}
     */
    public function save($id, $data, $lifeTime = 0) {
        return parent::save($id, $data, $lifeTime);
    }

    /**
     * {@inheritdoc}
     */
    public function saveMultiple(array $keysAndValues, $lifetime = 0) {
        return parent::saveMultiple($keysAndValues, $lifetime);
    }
}