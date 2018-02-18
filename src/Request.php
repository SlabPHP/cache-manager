<?php
/**
 * Slab cache request object
 *
 * @author Eric
 * @package Slab
 * @subpackage Cache
 */
namespace Slab\Cache;

class Request
{
    /**
     * Callback Object Reference
     *
     * @var \stdClass
     */
    private $callbackObject;

    /**
     * Callback Function
     *
     * @var string
     */
    private $callbackFunction;

    /**
     * Callback Parameters
     *
     * @var array
     */
    private $callbackParameters = array();

    /**
     * Cache Key
     *
     * @var string
     */
    private $cacheKey = "";

    /**
     * Cache TTL
     *
     * @var integer
     */
    private $ttl = 3600;

    /**
     * Force refresh
     *
     * @var boolean
     */
    private $forceRefresh = false;

    /**
     * @param bool $forceRefresh
     *
     * @return $this
     */
    public function setForceRefresh($forceRefresh = false)
    {
        $this->forceRefresh = $forceRefresh;

        return $this;
    }

    /**
     * Set the cache key
     *
     * @param string $cacheKey
     * @return $this
     */
    public function setCacheKey($cacheKey)
    {
        $this->cacheKey = $cacheKey;

        return $this;
    }

    /**
     * Set cache TTL
     *
     * @param integer $ttl
     * @return $this
     */
    public function setCacheTTL($ttl)
    {
        $this->ttl = $ttl;

        return $this;
    }

    /**
     * Set cache callback
     *
     * @param object $object
     * @param string $callbackFunction
     * @param array $parameters
     * @return $this
     */
    public function setCallback($object, $callbackFunction, $parameters = array())
    {
        $this->callbackObject = $object;

        $this->callbackFunction = $callbackFunction;

        $this->callbackParameters = $parameters;

        return $this;
    }

    /**
     * Get a cache key
     *
     * @return string
     */
    public function getCacheKey()
    {
        return $this->cacheKey;
    }

    /**
     * Get force refresh value
     *
     * @return boolean
     */
    public function getForceRefresh()
    {
        return $this->forceRefresh;
    }

    /**
     * Get cache TTL
     *
     * @return number
     */
    public function getCacheTTL()
    {
        return $this->ttl;
    }

    /**
     * Activate the callback function
     *
     * @return mixed
     */
    public function executeCallback()
    {
        if (method_exists($this->callbackObject, $this->callbackFunction)) {
            return call_user_func_array(array($this->callbackObject, $this->callbackFunction), $this->callbackParameters);
        }

        return null;
    }
}