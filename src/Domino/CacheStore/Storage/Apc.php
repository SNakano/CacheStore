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
 * Domino Cache Store Storage for APC
 *
 * @package Domino_CacheStore
 * @subpackage Storage
 */
class Apc implements StorageInterface
{
    /**
     * Namespace separator
     */
    const NAMESPACE_SEPARATOR = '\\';

    /**
     * Default expiration time (sec)
     * @var integer
     */
    private $default_ttl = 360;

    /**
     * Constructor
     * @param array $option cache store storage option
     */
    public function __construct($option = array())
    {
        $this->default_ttl = $option['default_ttl'];
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
        $store_key = $this->generateStoreKey($namespace, $key);
        $expire    = is_null($ttl) ? $this->default_ttl : $ttl;

        return apc_store($store_key, $value, $expire);
    }

    /**
     * Get item
     * @param  string $namespace
     * @param  string $key
     * @return mixed             stored item
     */
    public function get($namespace, $key)
    {
        $store_key = $this->generateStoreKey($namespace, $key);

        return apc_exists($store_key) ? apc_fetch($store_key) : null;
    }

    /**
     * Clear item
     * @param  string  $namespace
     * @param  string  $key
     * @return boolean            success or failure
     */
    public function clear($namespace, $key)
    {
        $store_key = $this->generateStoreKey($namespace, $key);

        return apc_delete($store_key);
    }

    /**
     * Clear by namespace
     * @param  string  $namespace
     * @return boolean            success or failure
     */
    public function clearByNamespace($namespace)
    {
        $pattern = '/^' . preg_quote($namespace . self::NAMESPACE_SEPARATOR) . '/';

        return apc_delete(new \APCIterator('user', $pattern, APC_ITER_VALUE));
    }

    /**
     * Clear all
     * @return boolean success or failure
     */
    public function clearAll()
    {
        return apc_clear_cache("user");
    }

    /**
     * Generate internal store key
     * @param  string $namespace
     * @param  string $key
     * @return string            internal store key
     */
    private function generateStoreKey($namespace, $key)
    {
        return $namespace . self::NAMESPACE_SEPARATOR . $key;
    }
}
