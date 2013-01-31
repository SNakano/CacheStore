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
class MemcachedTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        if (!extension_loaded('memcached')) {
            $this->markTestSkipped("Memcached extension is not loaded");
        }

        $option = array(
            'servers'     => array(array('localhost', 11211, 0)),
            'prefix'      => 'domino_test',
            'default_ttl' => 360,
        );

        $this->memcached = new Storage\Memcached($option);
        $this->memcached->clearAll();
    }

    public function testSetAndGet()
    {
        $this->memcached->set('namespace', 'key', 'value');
        $this->assertEquals('value', $this->memcached->get('namespace', 'key'));
    }

    public function testClear()
    {
        $this->memcached->set('namespace', 'key', 'value');
        $this->memcached->clear('namespace', 'key');
        $this->assertNull($this->memcached->get('namespace', 'key'));
    }

    public function testClearAll()
    {
        $this->memcached->set('namespace1', 'key1', 'value1');
        $this->memcached->set('namespace2', 'key2', 'value2');
        $this->memcached->clearAll();
        $this->assertNull($this->memcached->get('namespace1', 'key1'));
        $this->assertNull($this->memcached->get('namespace2', 'key2'));
    }

    public function testClearByNamespace()
    {
        $this->memcached->set('namespace1', 'key1', 'value1');
        $this->memcached->set('namespace2', 'key2', 'value2');
        $this->memcached->clearByNamespace('namespace1');
        $this->assertNull($this->memcached->get('namespace1', 'key1'));
        $this->assertEquals('value2', $this->memcached->get('namespace2', 'key2'));
    }
}