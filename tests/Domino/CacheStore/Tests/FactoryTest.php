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
        $set_apc_option = array("storage" => 'apc', 'default_ttl' => 10);
        CacheStore\Factory::setOption($set_apc_option);

        $set_memcached_option = array("storage" => 'memcached', 'option1' => 20);
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
}