<?php
/**
 * Cache Object, responsible for creating a provider object and issuing get/set calls to it
 *
 * @author Eric
 * @class Driver
 * @package Slab
 * @subpackage Cache
 *
 */
namespace Slab\Cache;

class Driver
{
    /**
     * Cache Provider Object
     *
     * @var \Slab\Cache\Providers\ProviderInterface
     */
    private $cacheProvider = NULL;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $log;

    /**
     * @param Providers\ProviderInterface $provider
     * @return $this
     */
    public function setProvider(Providers\ProviderInterface $provider)
    {
        $this->cacheProvider = $provider;

        return $this;
    }

    /**
     * @param \Psr\Log\LoggerInterface $log
     * @return $this
     */
    public function setLog(\Psr\Log\LoggerInterface $log)
    {
        $this->log = $log;

        return $this;
    }

    /**
     * Require a provider
     *
     * @param string $message
     * @return boolean
     */
    private function requireProvider($message = '')
    {
        if (empty($this->cacheProvider)) {
            if (!empty($this->log)) $this->log->notice("Cache provider is not available. " . $message);

            return false;
        }

        return true;
    }

    /**
     * Retrieve data from memcache
     *
     * @param string $key
     */
    public function get($key)
    {
        if (!$this->requireProvider("Getting key " . $key)) return false;

        return $this->cacheProvider->get($key);
    }

    /**
     * Saves data in memcache
     *
     * @param string $key
     * @param mixed $data
     * @param integer $ttl
     * @return boolean
     */
    public function set($key, $data, $ttl = 3600)
    {
        if (!$this->requireProvider("Setting key " . $key)) return false;

        return $this->cacheProvider->set($key, $data, $ttl);
    }

    /**
     * Delete a key from memcache
     *
     * @param string $key
     * @return boolean
     */
    public function delete($key)
    {
        if (!$this->requireProvider("Deleting key " . $key)) return false;

        return $this->cacheProvider->delete($key);
    }

    /**
     * Return the cache provider
     *
     * @return \Slab\Cache\Providers\Base
     */
    public function getProvider()
    {
        return $this->cacheProvider;
    }

    /**
     * Clear any cache calls on GET this time around
     */
    public function setCacheClearOnGet()
    {
        $this->cacheClearOnGet = true;

        return $this;
    }

    /**
     * Do a cache request
     *
     * @param Request $request
     * @return mixed
     */
    public function execute(Request $request)
    {
        if (!$this->requireProvider("Executing cache request")) return null;

        $cacheKey = $request->getCacheKey();

        if (empty($cacheKey)) {
            if (!empty($this->log)) $this->log->error("Invalid or missing cache key.");
            return null;
        }

        $cacheRefresh = $request->getForceRefresh();

        if (!$cacheRefresh) {
            $cacheData = $this->cacheProvider->get($cacheKey);

            if (!empty($cacheData)) {
                if (!empty($this->log)) $this->log->debug("Cache hit on key " . $cacheKey);

                return $cacheData;
            }
        }

        if (!empty($this->log)) $this->log->debug("CACHE", "Cache miss on key " . $cacheKey);

        $value = $request->executeCallback();

        if (!empty($value)) {
            $this->cacheProvider->set($cacheKey, $value, $request->getCacheTTL());
        }

        return $value;
    }
}


