<?php
/**
 * Base cache provider class
 *
 * @author Eric
 * @package Slab
 * @subpackage Cache
 */
namespace Slab\Cache\Providers;

abstract class Base implements ProviderInterface
{
    /**
     * Key suffix
     *
     * @var string
     */
    protected $keySuffix;

    /**
     * If you override the default constructor, make sure you set keySuffix
     */
    public function __construct()
    {
        $this->keySuffix = !empty($_SERVER['HTTP_HOST']) ? strtolower($_SERVER['HTTP_HOST']) : php_sapi_name();
    }

    /**
     * Add suffix string to a cache key
     *
     * @param string $additionalSuffix
     * @return $this
     */
    public function setKeySuffix($additionalSuffix)
    {
        $this->keySuffix .= $additionalSuffix;

        return $this;
    }

    /**
     * Get a key from cache
     *
     * @param string $key
     * @return mixed|NULL
     */
    abstract public function get($key);

    /**
     * Return a key from cache
     *
     * @param string $key
     * @param integer $TTL
     *
     * @return boolean
     */
    abstract public function set($key, $data, $TTL);

    /**
     * Delete/invalidate a key
     *
     * @param string $key
     * @return boolean
     */
    abstract public function delete($key);

    /**
     * Process a key
     *
     * @param string $key
     * @return string
     */
    protected function processKey($key)
    {
        return $key;
    }
}