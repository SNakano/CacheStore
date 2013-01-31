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
 * Domino Cache Store Storage Interface
 *
 * @package Domino_CacheStore
 * @subpackage Storage
 */
interface StorageInterface
{
    /**
     * Constructor
     * @param array $option cache store storage option
     */
    public function __construct($option = array());

    /**
     * Set item
     * @param string  $namespace
     * @param string  $key
     * @param mixed   $value
     * @param integer $ttl       expiration time (sec)
     */
    public function set($namespace, $key, $value, $ttl = null);

    /**
     * Get item
     * @param  string $namespace
     * @param  string $key
     * @return mixed             stored item
     */
    public function get($namespace, $key);

    /**
     * Clear item
     * @param  string  $namespace
     * @param  string  $key
     * @return boolean            success or failure
     */
    public function clear($namespace, $key);

    /**
     * Clear by namespace
     * @param  string  $namespace
     * @return boolean            success or failure
     */
    public function clearByNamespace($namespace);

    /**
     * Clear all
     * @return boolean success or failure
     */
    public function clearAll();
}