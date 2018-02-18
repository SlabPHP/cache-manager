<?php
/**
 * Memcache provider class
 *
 * @author Eric
 * @package Slab
 * @subpackage Cache
 */
namespace Slab\Cache\Providers;

class Memcache extends Base
{
    /**
     * Internal memcache object
     *
     * @var \Memcache
     */
    private $memcache = false;

    /**
     * @var bool
     */
    private $compress = true;

    /**
     * @param $hostname
     * @param int $port
     * @param bool $compress
     * @return $this
     * @throws \Exception
     */
    public function connect($hostname, $port = 11211, $compress = true)
    {
        $this->compress = $compress;

        if (!@extension_loaded('memcache')) {
            throw new \Exception("Memcache extension is not loaded.");
        }

        $memcache = new \Memcache();
        if (!$memcache->connect($hostname, $port))
        {
            throw new \Exception("Memcache failed to connect to " . $hostname . ':' . $port);
        }

        $this->memcache = $memcache;

        return $this;
    }

    /**
     * Retrieve data from memcache
     *
     * @param string $key
     * @return mixed|boolean
     */
    public function get($key)
    {
        if (empty($this->memcache)) {
            return false;
        }

        $key = $this->processKey($key);

        $data = $this->memcache->get($key);

        return $data;
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
        if (empty($this->memcache)) {
            return false;
        }

        $key = $this->processKey($key);

        $compress = !empty($this->compress) ? MEMCACHE_COMPRESSED : 0;

        return $this->memcache->set($key, $data, $compress, $ttl);
    }

    /**
     * Delete a key from memcache
     *
     * @param string $key
     * @return boolean
     */
    public function delete($key)
    {
        if (empty($this->memcache)) {
            return false;
        }

        $key = $this->processKey($key);

        return $this->memcache->delete($key);
    }

    /**
     * Flush the server
     *
     * @return boolean
     */
    public function flush()
    {
        if (empty($this->memcache)) {
            return false;
        }

        $flushStatus = $this->memcache->flush();

        return $flushStatus;
    }

    /**
     * @return \Memcache
     */
    public function getInterface()
    {
        return $this->memcache;
    }

    /**
     * Global post processing on memcache keys
     *
     * @param string $key
     * @return string
     */
    protected function processKey($key)
    {
        if (!empty($this->keySuffix)) {
            return $key . '|' . $this->keySuffix;
        } else {
            return $key;
        }
    }

}


