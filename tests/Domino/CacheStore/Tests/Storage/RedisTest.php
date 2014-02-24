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
 * Domino Cache Store Storage for Redis Test
 *
 * @package Domino_CacheStore
 * @subpackage UnitTests
 */
class RedisTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        if (!class_exists("Redis")) {
            $this->markTestSkipped("Redis is not enabled");
        }
        $option = array(
            'host'        => '127.0.0.1',
            'port'        => '6379',
            'prefix'      => 'domino_test',
            'default_ttl' => 360,
        );
        $this->redis = new Storage\Redis($option);
        $this->redis->clearAll();
    }

    public function testSetAndGet()
    {
        $this->redis->set('namespace', 'key', 'value');
        $this->assertEquals('value', $this->redis->get('namespace', 'key'));
    }

    public function testClear()
    {
        $this->redis->set('namespace', 'key', 'value');
        $this->redis->clear('namespace', 'key');
        $this->assertNull($this->redis->get('namespace', 'key'));
    }

    public function testClearAll()
    {
        $this->redis->set('namespace1', 'key1', 'value1');
        $this->redis->set('namespace2', 'key2', 'value2');
        $this->redis->clearAll();
        $this->assertNull($this->redis->get('namespace1', 'key1'));
        $this->assertNull($this->redis->get('namespace2', 'key2'));
    }

    public function testClearByNamespace()
    {
        $this->redis->set('namespace1', 'key1', 'value1');
        $this->redis->set('namespace2', 'key2', 'value2');
        $this->redis->clearByNamespace('namespace1');
        $this->assertNull($this->redis->get('namespace1', 'key1'));
        $this->assertEquals('value2', $this->redis->get('namespace2', 'key2'));
    }
}