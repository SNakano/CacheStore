<?php
/**
 * Domino Cache Store
 *
 * @copyright Copyright (c) 2013 Domino Co. Ltd.
 * @license MIT
 * @package Domino_CacheStore
 */

namespace Domino\CacheStore\Tests;

use Domino\CacheStore;

/**
 * Domino Cache Store Storage Factory Test
 *
 * @package Domino_CacheStore
 * @subpackage UnitTests
 */
class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        CacheStore\Factory::clearOptions();
        CacheStore\Factory::enableCaching();
    }

    public function testOptions()
    {
        $options = array('storage' => 'apc');

        CacheStore\Factory::setOptions($options);
        $result = CacheStore\Factory::getOptions();

        $this->assertEquals($options, $result);
    }

    public function testOption()
    {
        $set_apc_option = array('storage' => 'apc', 'default_ttl' => 10);
        CacheStore\Factory::setOption($set_apc_option);

        $set_memcached_option = array('storage' => 'memcached', 'option1' => 20);
        CacheStore\Factory::setOption($set_memcached_option);

        $get_apc_option = CacheStore\Factory::getOption('apc');
        $this->assertEquals($set_apc_option, $get_apc_option);

        $get_memcached_option = CacheStore\Factory::getOption('memcached');
        $this->assertEquals($set_memcached_option, $get_memcached_option);
    }

    public function testFactoryApc()
    {
        $apc_option = array('storage' => 'apc', 'default_ttl' => 10);
        CacheStore\Factory::setOption($apc_option);

        $cacheStore = CacheStore\Factory::factory('apc');
        $this->assertInstanceOf('Domino\CacheStore\Storage\Apc', $cacheStore);
    }

    public function testFactoryMemcached()
    {
        if (!extension_loaded('memcached')) {
            $this->markTestSkipped('Memcached extension is not loaded');
        }

        $memcached_option = array('storage' => 'memcached', 'default_ttl' => 10, 'prefix' => '_md', 'servers' => array());
        CacheStore\Factory::setOption($memcached_option);

        $cacheStore = CacheStore\Factory::factory('memcached');
        $this->assertInstanceOf('Domino\CacheStore\Storage\Memcached', $cacheStore);
    }

    public function testFactoryMemcache()
    {
        if (!extension_loaded('memcache')) {
            $this->markTestSkipped('Memcache extension is not loaded');
        }

        $memcached_option = array('storage' => 'memcache', 'default_ttl' => 10, 'prefix' => '_md', 'servers' => array());
        CacheStore\Factory::setOption($memcached_option);

        $cacheStore = CacheStore\Factory::factory('memcache');
        $this->assertInstanceOf('Domino\CacheStore\Storage\Memcache', $cacheStore);
    }

    public function testRegisterStorageNotRegistred()
    {
        $custom_option = array('storage' => 'custom');
        CacheStore\Factory::setOption($custom_option);

        $this->setExpectedException('Domino\CacheStore\Exception\StorageException');
        CacheStore\Factory::factory('custom');
    }

    public function testRegisterStorage()
    {
        $custom_option = array('storage' => 'custom');
        CacheStore\Factory::setOption($custom_option);

        // register custom storage
        $customStorage = $this->getMock('Domino\CacheStore\Storage\StorageInterface');
        CacheStore\Factory::registerStorage('custom', get_class($customStorage));
        $cacheStore = CacheStore\Factory::factory('custom');
        $this->assertInstanceOf(get_class($customStorage), $cacheStore);

        // try to register custom storage with bad interface
        $this->setExpectedException('Domino\CacheStore\Exception\StorageException');
        CacheStore\Factory::registerStorage('custom2', '\stdClass');
        CacheStore\Factory::factory('custom2');
    }

    public function testConnectionCache()
    {
        $apc_option = array('storage' => 'apc', 'default_ttl' => 10);
        CacheStore\Factory::setOption($apc_option);

        $store1 = CacheStore\Factory::factory('apc');
        $store2 = CacheStore\Factory::factory('apc');

        $this->assertSame($store1, $store2);
    }

    public function testClearConnectionCache()
    {
        $apc_option = array('storage' => 'apc', 'default_ttl' => 10);
        CacheStore\Factory::setOption($apc_option);

        $store1 = CacheStore\Factory::factory('apc');
        CacheStore\Factory::clearConnectionCache();
        $store2 = CacheStore\Factory::factory('apc');

        $this->assertNotSame($store1, $store2);
    }

    public function testDisableCache()
    {
        CacheStore\Factory::disableCaching();
        $storage = CacheStore\Factory::factory('apc');

        $this->assertInstanceOf('\Domino\CacheStore\Storage\NoCache', $storage);
    }

    public function testDisableAndEnableCache()
    {
        //begin of setup
        $name           = 'custom';
        /** @var CacheStore\Storage\StorageInterface $storageMock */
        $storageMock    = $this->getMock('Domino\CacheStore\Storage\StorageInterface');
        CacheStore\Factory::registerStorage($name, get_class($storageMock));
        //end of setup

        //begin of assertions
        $customStorage  = CacheStore\Factory::factory($name);
        CacheStore\Factory::disableCaching();
        $noCacheStorage = CacheStore\Factory::factory($name);

        $this->assertInstanceOf('\Domino\CacheStore\Storage\NoCache', $noCacheStorage);
        CacheStore\Factory::enableCaching();

        $this->assertSame($customStorage, CacheStore\Factory::factory($name));
        //end of assertions
    }
}
