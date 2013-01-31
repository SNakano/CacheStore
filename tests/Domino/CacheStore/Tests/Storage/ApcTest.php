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
 * Domino Cache Store Storage for APC Test
 *
 * @package Domino_CacheStore
 * @subpackage UnitTests
 */
class ApcTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        if (!ini_get('apc.enable_cli')) {
            $this->markTestSkipped("APC is not enabled");
        }

        $this->apc = new Storage\Apc(array('default_ttl' => 360));
        $this->apc->clearAll();
    }

    public function testSet()
    {
        $result = $this->apc->set('namespace', 'key', 'value');
        $this->assertTrue($result);
        $this->assertEquals('value', apc_fetch('namespace\key'));
    }

    public function testGet()
    {
        apc_store('namespace\key', 'value');
        $this->assertEquals('value', $this->apc->get('namespace', 'key'));
    }

    public function testGetWhenKeyNotExist()
    {
        $this->assertNull($this->apc->get('namespace', 'key'));
    }

    public function testClear()
    {
        $this->apc->set('namespace', 'key', 'value');
        $this->apc->clear('namespace', 'key');

        $this->assertNull($this->apc->get('namespace', 'key'));
    }

    public function testClearByNamespace()
    {
        $this->apc->set('namespace1', 'key1', 'value1');
        $this->apc->set('namespace1', 'key2', 'value2');
        $this->apc->set('namespace2', 'key3', 'value3');

        $this->apc->clearByNamespace('namespace1');
        $this->assertNull($this->apc->get('namespace1', 'key1'));
        $this->assertNull($this->apc->get('namespace1', 'key2'));
        $this->assertEquals('value3', $this->apc->get('namespace2', 'key3'));
    }
}