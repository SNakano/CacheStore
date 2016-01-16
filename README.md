# DominoCacheStore

[![Build Status](https://travis-ci.org/SNakano/CacheStore.png)](https://travis-ci.org/SNakano/CacheStore)
[![Latest Stable Version](https://poser.pugx.org/snakano/cache-store/v/stable.svg)](https://packagist.org/packages/snakano/cache-store)
[![Total Downloads](https://poser.pugx.org/snakano/cache-store/downloads.svg)](https://packagist.org/packages/snakano/cache-store)
[![License](https://poser.pugx.org/snakano/cache-store/license.svg)](https://packagist.org/packages/snakano/cache-store)

provides a generic way to cache any data.

- It provide common interface for cache library.
- support cache library APC, memcached and Redis.
- support namespace. (use namespace delete)


## Install

using Composer(recommended):

```javascript
{
    "require": {
        "snakano/cache-store": "1.*"
    }
}
```
or cloning this repository.


## Usage

```php
// configure cache setting.
Domino\CacheStore\Factory::setOptions(
    array(
        array(
            'storage'     => 'apc'
            'default_ttl' => 360
        ),
        array(
            'storage'     => 'memcached',
            'prefix'      => 'domino_test',
            'default_ttl' => 360,
            'servers'     => array(
                array('server1', 11211, 20),
                array('server2', 11211, 80)
            )
        ),
        array(
            'storage'     => 'redis',
            'prefix'      => 'domino_test',
            'host'        => '127.0.0.1',
            'port'        => 6379,
            'default_ttl' => 360
        )
    )
);

// factory cache storage
$storage = Domino\CacheStore\Factory::factory('memcached');

// register custom cache storage
$storage = Domino\CacheStore\Factory::registerStorage('acme_storage', 'Acme\Storage\AcmeStorage');

// set data
$storage->set('ns1', 'key1', 'value1');
$storage->set('ns1', 'key2', 'value2');
$storage->set('ns2', 'key1', 'value1');
$storage->set('ns2', 'key2', 'value2', 10); // specify ttl

// get data
$storage->get('ns1', 'key1');
# => 'value1'

// delete by namespace and key
$storage->clear("ns1", 'key1');
$storage->get('ns1', 'key1');
# => null

// delete by namespace
$storage->clearByNamespace('ns1');
$storage->get('ns1', 'key2');
# => null
$storage->get('ns2', 'key1');
# => 'value1'

// delete all
$storage->clearAll();
$storage->get('ns2', 'key1');
# => null

// disable caching at runtime
Domino\CacheStore\Factory::disableCaching();
// since caching is disabled, the factory will return an instance of "NoCache"
$storage = Domino\CacheStore\Factory::factory('memcached');

// enable caching at runtime
Domino\CacheStore\Factory::enableCaching();
// since caching is enabled again, the factory will return an instance of "Memcached"
$storage = Domino\CacheStore\Factory::factory('memcached');
```

## License

MIT license.
