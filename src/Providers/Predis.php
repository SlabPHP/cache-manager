<?php
/**
 * Predis Cache Provider for wrapping the predis library in a Slab Context
 *
 * @author eric
 * @package Slab
 * @subpackage Cache
 */
namespace Slab\Cache\Providers;

class Predis extends Base
{
    /**
     * Predis Object
     *
     * @var \Predis
     */
    private $predis;

    /**
     * Key suffix
     *
     * @var string
     */
    protected $keySuffix;

    /**
     * Set cache key suffix
     *
     * @param string $cacheKeySuffix
     */
    public function setCacheKeySuffix($cacheKeySuffix)
    {
        $this->keySuffix = $cacheKeySuffix;
    }

    /**
     * Connect
     *
     * @param string $host
     * @param int $port
     * @param string $scheme
     * @param bool $persistent
     * @return $this
     * @throws \Exception
     */
    public function connect($host = 'localhost', $port = 6379, $scheme = 'tcp', $persistent = true)
    {
        if (!class_exists('\Predis\Client'))
        {
            throw new \Exception("You must include the predis library to use this cache provider.");
        }

        $this->predis = new \Predis\Client([
            'host' => $host,
            'port' => $port,
            'scheme' => $scheme,
            'persistent' => $persistent
        ]);

        //$this->_predis->info();

        return $this;
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        if (!empty($this->predis)) {
            $this->predis->quit();
        }
    }

    /**
     * Get a key from cache
     *
     * @param string $key
     * @return mixed|NULL
     */
    public function get($key)
    {
        if (empty($this->predis)) return false;

        $key = $this->processKey($key);

        $data = $this->predis->get($key);

        return unserialize($data);
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
        if (empty($this->predis)) return false;

        $key = $this->processKey($key);

        $returnValue = $this->predis->set($key, serialize($data));

        if (!empty($TTL)) {
            $this->predis->expire($key, $TTL);
        }

        return $returnValue;
    }

    /**
     * Delete/invalidate a key
     *
     * @param string $key
     * @return boolean
     */
    public function delete($key)
    {
        if (empty($this->predis)) return false;

        $key = $this->processKey($key);

        return $this->predis->del($key);
    }

    /**
     * Flush all entries in provider
     *
     * @return boolean
     */
    public function flush()
    {
        if (empty($this->predis)) return false;

        $this->predis->flushAll();
    }

    /**
     * Get a private interface
     *
     * @return mixed
     */
    public function getInterface()
    {
        return $this->predis;
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