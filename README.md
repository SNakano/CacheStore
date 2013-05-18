DominoDataCache
=================
[![Build Status](https://travis-ci.org/SNakano/DataCache.png)](https://travis-ci.org/SNakano/DataCache)

provides a generic way to cache any data.

- It provide common interface for cache library.
- support cache library APC and memcached.
- support namespace. (use namespace delete)


Install
-------

using Composer(recommended):

```javascript
{
    "require": {
        "SNakano/DataCache": "dev-master"
    }
}
```
or cloning this repository.


Usage
-----

```php
// configure cache setting.
Domino\Factory::setOptions(
    array(
        array(
            'storage'     => 'apc'
            'default_ttl' => 360
        ),
        array(
            'strage'      => 'memcached',
            'prefix'      => 'domino_test',
            'default_ttl' => 360,
            'servers'     => array(
                array('server1', 11211, 20),
                array('server2', 11211, 80)
            )
        )
    )
);

// factory cache storage
$storage = Domino\Factory::factory('memcached');

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
```

License
-------

MIT license.
