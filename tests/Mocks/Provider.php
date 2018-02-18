<?php

namespace Slab\Tests\Cache\Mocks;

class Provider implements \Slab\Cache\Providers\ProviderInterface
{
    /**
     * Get a key from cache
     *
     * @param string $key
     * @return mixed|NULL
     */
    public function get($key)
    {
        return $key;
    }

    /**
     * Return a key from cache
     *
     * @param string $key
     * @param integer $TTL
     *
     * @return boolean
     */
    public function set($key, $data, $TTL)
    {
        return true;
    }

    /**
     * Delete/invalidate a key
     *
     * @param string $key
     * @return boolean
     */
    public function delete($key)
    {
        return true;
    }
}