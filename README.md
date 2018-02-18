# SlabPHP Cache Manager

The SlabPHP Cache Management Driver Library wraps several different types of cache providers and gives a single interface to them.

This library is deprecated but since it's still part of the SlabPHP monorepo it's being open sourced as well. There are better alternatives that adopt PSR standards that you should use. The author of this library doesn't even consider wrapping all these different libraries together as a good pattern for cache. Please see the main SlabPHP documentation for more information about this and the other SlabPHP repositories.

## Installation and Setup

First include this with composer:

    composer require slabphp/cache-manager

Then create your provider object:

    $provider = new \Slab\Cache\Providers\Predis();
    $provider
        ->setHost('locallhost', 6379);

    $driver = new \Slab\Cache\Driver();
    $driver
        ->setProvider($provider);

## Usage

### Using a Request Object

The Request object is designed to wrap the getting and setting of cache in one basic step.

    $request = new \Slab\Cache\Request();

    $request
        ->setCacheKey('my-key')
        ->setCacheTTL(3600)
        ->setCallback($someObject, 'someCallBackFunction', ['value1'])
        ->setForceRefresh(!empty($_GET['cacheRefresh']));

    $output = $driver->execute($request);

What this does is first check the set cache provider for a value with the key 'my-key'. If it exists, it will return it. Otherwise, it will execute $someObject->someCallBackFunction('value1'), save the value with key 'my-key' and a ttl of 3600 seconds, and then return it.

### Other ways

You can do get(), set(), and delete() on the driver and they will pass through to the provider. You can also do ->getProvider() on the driver to perform any provider specific actions.