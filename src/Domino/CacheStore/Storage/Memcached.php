<?php
/**
 * Domino Cache Store
 *
 * @copyright Copyright (c) 2013 Domino Co. Ltd.
 * @license MIT
 * @package Domino_CacheStore
 */

namespace Domino\CacheStore\Storage;

/**
 * Domino Cache Store Storage for Memcached
 *
 * @package Domino_CacheStore
 * @subpackage Storage
 */
class Memcached implements StorageInterface
{
    /**
     * key name separator
     */
    const SEPARATOR = '-';

    /**
     * Memcached Instance
     * @var Memcached
     */
    private $connect = null;

    /**
     * Prefix key
     * @var string
     */
    private $prefix = null;

    /**
     * Default expiration time (sec)
     * @var integer
     */
    private $default_ttl = null;

    /**
     * Constructor
     * @param array $options cache store storage option
     */
    public function __construct($options = array())
    {
        $this->connect     = new \Memcached;
        $this->prefix      = $options['prefix'];
        $this->default_ttl = $options['default_ttl'];
        $this->connect->setOption(\Memcached::OPT_PREFIX_KEY, $this->prefix);
        $this->connect->addServers($options['servers']);
    }

    /**
     * Set item
     * @param string  $namespace
     * @param string  $key
     * @param mixed   $value
     * @param integer $ttl       expiration time (sec)
     */
    public function set($namespace, $key, $value, $ttl = null)
    {
        $store_key = $this->getStoreKey($namespace, $key);
        $expire    = is_null($ttl) ? $this->default_ttl : $ttl;

        return $this->connect->set($store_key, $value, $expire);
    }

    /**
     * Get item
     * @param  string $namespace
     * @param  string $key
     * @return mixed             stored item
     */
    public function get($namespace, $key)
    {
        $store_key = $this->getStoreKey($namespace, $key);
        $value     = $this->connect->get($store_key);

        if ($this->connect->getResultCode() == \Memcached::RES_NOTFOUND) {
            return null;
        }

        return $value;
    }

    /**
     * Clear item
     * @param  string  $namespace
     * @param  string  $key
     * @return boolean            success or failure
     */
    public function clear($namespace, $key)
    {
        $store_key = $this->getStoreKey($namespace, $key);

        return $this->connect->delete($store_key);
    }

    /**
     * Clear by namespace
     * @param  string  $namespace
     * @return boolean            success or failure
     */
    public function clearByNamespace($namespace)
    {
        $version_key = $this->getVersionKey($namespace);

        return $this->connect->increment($version_key);
    }

    /**
     * Clear all
     * @return boolean success or failure
     */
    public function clearAll()
    {
        return $this->connect->flush();
    }

    /**
     * Generate internal store key
     * @param  string $namespace
     * @param  string $key
     * @return string            internal store key
     */
    private function getStoreKey($namespace, $key)
    {
        $version   = $this->getVersion($namespace);
        $store_key = $namespace . self::SEPARATOR . $version . self::SEPARATOR . $key;

        return $store_key;
    }

    /**
     * Get namespace version
     * @param  string  $namespace
     * @return integer            namespace version
     */
    private function getVersion($namespace)
    {
        if ($version = $this->connect->get($this->getVersionKey($namespace))) {
            return $version;
        }
        // initialize version number;
        $this->connect->set($this->getVersionKey($namespace), 1);

        return 1;
    }

    /**
     * Generate internal store version key
     * @param  string $namespace
     * @return string            internal store version key
     */
    private function getVersionKey($namespace)
    {
        return $namespace . self::SEPARATOR . "version";
    }
}