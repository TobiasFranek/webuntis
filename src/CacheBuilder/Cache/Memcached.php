<?php
namespace Webuntis\CacheBuilder\Cache;


use Doctrine\Common\Cache\MemcachedCache;
use Webuntis\Repositories\Repository;

/**
 * The Memcache caching instance which is use to cache the given data
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class Memcached extends MemcachedCache {

    /**
     * {@inheritdoc}
     */
    public function contains($id) {
        return parent::contains('webuntis.' . $id);
    }

    /**
     * {@inheritdoc}
     */
    public function delete($id) {
        return parent::delete('webuntis.' . $id);
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
        return parent::fetch('webuntis.' . $id);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchMultiple(array $keys) {
        foreach($keys as $i => $key) {
            $keys[$i] = 'webuntis.' . $key;
        }
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
        return parent::save('webuntis.' . $id, $data, $lifeTime);
    }

    /**
     * {@inheritdoc}
     */
    public function saveMultiple(array $keysAndValues, $lifetime = 0) {
        foreach($keysAndValues as $key => $value) {
            $keysAndValues['webuntis.' . $key] = $value;
            unset($keysAndValues[$key]);
        }
        return parent::saveMultiple($keysAndValues, $lifetime);
    }
}