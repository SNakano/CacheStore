<?php
/**
 * Domino Cache Store
 *
 * @copyright Copyright (c) 2013 Domino Co. Ltd.
 * @license MIT
 * @package Domino_CacheStore
 */

namespace Domino\CacheStore;

use Domino\CacheStore\Exception\StorageException;
use Domino\CacheStore\Storage\StorageInterface;

/**
 * Domino Cache Store Factory
 *
 * @package Domino_CacheStore
 * @subpackage Storage
 */
class Factory
{
    /**
     * Cache store storage options
     * @var array
     */
    private static $options = array();

    /**
     * @var array Cache of established connections (to eliminate overhead).
     */
    private static $connectionMap = array();

    /**
     * Registered storage
     * @var array
     */
    private static $storage = array(
        'apc'       => 'Domino\CacheStore\Storage\Apc',
        'memcached' => 'Domino\CacheStore\Storage\Memcached',
        'memcache'  => 'Domino\CacheStore\Storage\Memcache',
        'redis'     => 'Domino\CacheStore\Storage\Redis',
    );

    /**
     * Set cache store storage options
     * @param array $options cache store storage options
     */
    public static function setOptions($options = array())
    {
        self::$options = $options;

        // clear cache storage instances
        self::$connectionMap = array();
    }

    /**
     * Get cache store storage options
     * @return array cache store storage options
     */
    public static function getOptions()
    {
        return self::$options;
    }

    /**
     * Set cache store storage option
     * @param array $option cache store storage option
     */
    public static function setOption($option = array())
    {
        self::$options[] = $option;
    }

    /**
     * Get cache store storage option
     * @param  string        $storage_type cache store storage type (eg. 'apc', 'memcached')
     * @return array                       cache store storage option
     */
    public static function getOption($storage_type)
    {
        foreach (self::$options as $option) {
            if ($option['storage'] == $storage_type) {
                return $option;
            }
        }

        return false;
    }

    /**
     * Clear cache store storage options
     */
    public static function clearOptions()
    {
        self::$options = array();
    }

    /**
     * Clear connection cache
     */
    public static function clearConnectionCache()
    {
        self::$connectionMap = array();
    }

    /**
     * Instantiate a cache storage
     * @param  string  $storage_type cache store storage type (eg. 'apc', 'memcached')
     * @return StorageInterface      cache store storage instance
     * @throws StorageException      when $storage_type is not registered
     */
    public static function factory($storage_type)
    {
        if (!array_key_exists($storage_type, self::$storage)) {
            throw new StorageException(sprintf('Storage class not set for type %s', $storage_type));
        }
        if (!isset(self::$connectionMap[$storage_type])) {
            self::$connectionMap[$storage_type] = new self::$storage[$storage_type](self::getOption($storage_type));
        }

        return self::$connectionMap[$storage_type];
    }

    /**
     * Register a cache storage
     *
     * @param $storage_type cache store storage type (eg. 'apc', 'memcached', 'my_apc')
     * @param $storage_class class name which must implement Domino\CacheStore\Storage\StorageInterface
     * @throws StorageException when $storage_class not implements Domino\CacheStore\Storage\StorageInterface
     */
    public static function registerStorage($storage_type, $storage_class)
    {
        $interface = 'Domino\CacheStore\Storage\StorageInterface';
        if (!in_array($interface, class_implements($storage_class, true))) {
            throw new StorageException(sprintf('Class %s must implements %s ', $storage_class, $interface));
        }
        self::$storage[$storage_type] = $storage_class;
    }
}
