<?php
/**
 * Domino Cache Store
 *
 * @copyright Copyright (c) 2013 Domino Co. Ltd.
 * @license MIT
 * @package Domino_CacheStore
 */

namespace Domino\CacheStore\Tests\Storage;

use Domino\CacheStore\Storage;

/**
 * Domino Cache Store Storage for Memcached Test
 *
 * @package Domino_CacheStore
 * @subpackage UnitTests
 */
class MemcacheTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Storage\Memcache
     */
    public $memcache;

    public function setUp()
    {
        if (!extension_loaded('memcache')) {
            $this->markTestSkipped("Memcache extension is not loaded");
        }

        $option = array(
            'servers'     => array(array('localhost', 11211, 0)),
            'prefix'      => 'domino_test',
            'default_ttl' => 360,
        );

        $this->memcache = new Storage\Memcache($option);
        $this->memcache->clearAll();
    }

    public function testObjectIsInstanceOfStorageInterface(){
        $this->assertInstanceOf('Domino\CacheStore\Storage\StorageInterface', $this->memcache);
    }

    /**
     * @depends testObjectIsInstanceOfStorageInterface
     */
    public function testSetAndGet()
    {
        $this->memcache->set('namespace', 'key', 'value');
        $this->assertEquals('value', $this->memcache->get('namespace', 'key'));
    }

    /**
     * @depends testObjectIsInstanceOfStorageInterface
     */
    public function testClear()
    {
        $this->memcache->set('namespace', 'key', 'value');
        $this->memcache->clear('namespace', 'key');
        $this->assertFalse($this->memcache->get('namespace', 'key'));
    }

    /**
     * @depends testObjectIsInstanceOfStorageInterface
     */
    public function testClearAll()
    {
        $this->memcache->set('namespace1', 'key1', 'value1');
        $this->memcache->set('namespace2', 'key2', 'value2');
        $this->memcache->clearAll();
        $this->assertFalse($this->memcache->get('namespace1', 'key1'));
        $this->assertFalse($this->memcache->get('namespace2', 'key2'));
    }

    /**
     * @depends testObjectIsInstanceOfStorageInterface
     */
    public function testClearByNamespace()
    {
        $this->memcache->set('namespace1', 'key1', 'value1');
        $this->memcache->set('namespace2', 'key2', 'value2');
        $this->memcache->clearByNamespace('namespace1');
        $this->assertFalse($this->memcache->get('namespace1', 'key1'));
        $this->assertEquals('value2', $this->memcache->get('namespace2', 'key2'));
    }
}
