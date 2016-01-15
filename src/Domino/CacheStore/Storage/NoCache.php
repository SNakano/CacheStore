<?php
/**
 * @author: stev leibelt <artodeto@bazzline.net>
 * @since: 2016-01-15
 */
namespace Domino\CacheStore\Storage;

/**
 * Class NoCache
 *
 * @package Domino\CacheStore\Storage
 */
class NoCache implements StorageInterface
{
    /**
     * Constructor
     *
     * @param array $option cache store storage option
     */
    public function __construct($option = array()) {}

    /**
     * Set item
     *
     * @param string $namespace
     * @param string $key
     * @param mixed $value
     * @param integer $ttl expiration time (sec)
     */
    public function set($namespace, $key, $value, $ttl = null) {}

    /**
     * Get item
     *
     * @param  string $namespace
     * @param  string $key
     * @return mixed             stored item
     */
    public function get($namespace, $key)
    {
        return null;
    }

    /**
     * Clear item
     *
     * @param  string $namespace
     * @param  string $key
     * @return boolean            success or failure
     */
    public function clear($namespace, $key)
    {
        return true;
    }

    /**
     * Clear by namespace
     *
     * @param  string $namespace
     * @return boolean            success or failure
     */
    public function clearByNamespace($namespace)
    {
        return true;
    }

    /**
     * Clear all
     *
     * @return boolean success or failure
     */
    public function clearAll()
    {
        return true;
    }
}
