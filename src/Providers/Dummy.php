<?php
/**
 * Dummy Cache Object
 *
 * Always results in a miss but can be used for debugging or fallbacks
 *
 * @author eric
 * @package Slab
 * @subpackage Cache
 */
namespace Slab\Cache\Providers;

class Dummy extends Base
{
    /**
     * Get a key from cache
     *
     * @param string $key
     * @return mixed|NULL
     */
    public function get($key)
    {
        return NULL;
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