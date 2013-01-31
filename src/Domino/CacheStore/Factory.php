<?php
/**
 * Domino Cache Store
 *
 * @copyright Copyright (c) 2013 Domino Co. Ltd.
 * @license MIT
 * @package Domino_CacheStore
 */

namespace Domino\CacheStore;

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
     * Set cache store storage options
     * @param array $options cache store storage options
     */
    public static function setOptions($options = array())
    {
        self::$options = $options;
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
     * Instantiate a cache storage
     * @param  string  $storage_type cache store storage type (eg. 'apc', 'memcached')
     * @return Storage               cache store storage instance
     */
    public static function factory($storage_type)
    {
        $storage_class = 'Domino\CacheStore\Storage\\' . ucfirst($storage_type);

        return new $storage_class(self::getOption($storage_type));
    }
}
