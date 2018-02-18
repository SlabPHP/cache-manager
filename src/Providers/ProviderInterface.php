<?php
/**
 * Slab Cache Provider Interface
 *
 * @package Slab
 * @subpackage Cache
 * @author Eric
 */
namespace Slab\Cache\Providers;

interface ProviderInterface
{
    /**
     * Get a key from cache
     *
     * @param string $key
     * @return mixed|NULL
     */
    public function get($key);

    /**
     * Return a key from cache
     *
     * @param string $key
     * @param integer $TTL
     *
     * @return boolean
     */
    public function set($key, $data, $TTL);

    /**
     * Delete/invalidate a key
     *
     * @param string $key
     * @return boolean
     */
    public function delete($key);
}