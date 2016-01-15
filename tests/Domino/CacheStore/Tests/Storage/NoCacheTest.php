<?php
/**
 * @author: stev leibelt <artodeto@bazzline.net>
 * @since: 2016-01-15
 */
namespace Domino\CacheStore\Tests\Storage;

use Domino\CacheStore\Storage\NoCache;

class NoCacheTest extends \PHPUnit_Framework_TestCase
{
    /** @var NoCache */
    private $storage;

    public function setUp()
    {
        $this->storage = new NoCache(array());
    }

    public function testSet()
    {
        $this->storage->set('namespace', 'key', 'value');
    }

    public function testGet()
    {
        $result = $this->storage->get('namespace', 'key');
        $this->assertNull($result);
    }

    public function testGetWhenKeyNotExist()
    {
        $this->assertNull($this->storage->get('namespace', 'key'));
    }

    public function testClear()
    {
        $this->storage->set('namespace', 'key', 'value');
        $this->storage->clear('namespace', 'key');

        $this->assertNull($this->storage->get('namespace', 'key'));
    }

    public function testClearByNamespace()
    {
        $this->storage->set('namespace1', 'key1', 'value1');
        $this->storage->set('namespace1', 'key2', 'value2');
        $this->storage->set('namespace2', 'key3', 'value3');

        $this->storage->clearByNamespace('namespace1');
        $this->assertNull($this->storage->get('namespace1', 'key1'));
        $this->assertNull($this->storage->get('namespace1', 'key2'));
        $this->assertNull($this->storage->get('namespace2', 'key3'));
    }
}
