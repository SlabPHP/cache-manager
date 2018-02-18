<?php
/**
 * File based cache provider
 *
 * @author Eric
 * @package Slab
 * @subpackage Cache
 * @class File
 */
namespace Slab\Cache\Providers;

class File extends Base
{
    /**
     * Cache directory
     *
     * @var string
     */
    private $cacheDir = '/tmp';

    /**
     * Maximum number of retries
     *
     * @var integer
     */
    const MAX_RETRIES = 100;

    /**
     * Milliseconds between retries
     *
     * @var integer
     */
    const MS_BETWEEN_RETRIES = 100;

    /**
     * TTL for Files is checked on read, so come up with a better way to set this
     *
     * @var integer
     */
    protected $ttl = 600;

    /**
     * Set TTL
     *
     * @param $ttl
     */
    public function setTTL($ttl)
    {
        $this->ttl = $ttl;
    }

    /**
     * @param $cacheDir
     * @return $this
     * @throws \Exception
     */
    public function setCacheDirectory($cacheDir)
    {
        if (!is_dir($cacheDir))
        {
            throw new \Exception("Invalid cache directory specified: " . $cacheDir);
        }

        $this->cacheDir = $cacheDir;

        return $this;
    }

    /**
     * Get a key from cache
     *
     * @param string $key
     * @return mixed|NULL
     */
    public function get($key)
    {
        $filename = $this->cacheDir . DIRECTORY_SEPARATOR . $this->processKey($key);

        if (is_file($filename) && is_readable($filename)) {
            if (@filemtime($filename) < (time() - $this->ttl)) {
                return null;
            } else {
                return unserialize($this->safelyReadData($filename));
            }
        }

        return null;
    }

    /**
     * Safely read data from a file
     *
     * @param string $path
     * @return mixed
     */
    private function safelyReadData($path)
    {
        $fp = fopen($path, 'r');

        $retries = 0;
        do {
            if ($retries > 0) {
                usleep(static::MS_BETWEEN_RETRIES * 1000); //100ms
            }

            $retries++;
        } while (!flock($fp, LOCK_SH | LOCK_NB) && $retries <= static::MAX_RETRIES);

        if ($retries == static::MAX_RETRIES) {
            return false;
        }

        $data = file_get_contents($path);

        flock($fp, LOCK_UN);
        fclose($fp);

        return $data;
    }

    /**
     * Safely write data to a file
     *
     * @param string $path
     * @param string $mode
     * @param string $data
     *
     * @return boolean
     */
    private function safelyWriteData($path, $mode, $data)
    {
        $fp = fopen($path, $mode);
        $retries = 0;

        if (empty($fp)) {
            return false;
        }

        do {
            if ($retries > 0) {
                usleep(static::MS_BETWEEN_RETRIES * 1000);
            }

            $retries++;
        } while (!flock($fp, LOCK_EX) && $retries <= static::MAX_RETRIES);

        if ($retries == static::MAX_RETRIES) {
            return false;
        }


        fwrite($fp, "$data\n");

        flock($fp, LOCK_UN);
        fclose($fp);

        return true;
    }

    /**
     * Return a key from cache
     *
     * @param string $key
     * @param mixed $data
     * @param integer $ttl
     *
     * @return boolean
     */
    public function set($key, $data, $ttl)
    {
        $this->ttl = $ttl;

        $filename = $this->cacheDir . DIRECTORY_SEPARATOR . $this->processKey($key);

        return $this->safelyWriteData($filename, 'w', serialize($data));
    }

    /**
     * Delete/invalidate a key
     *
     * @param string $key
     * @return boolean
     */
    public function delete($key)
    {
        $filename = $this->cacheDir . DIRECTORY_SEPARATOR . $this->processKey($key);

        return unlink($filename);
    }

    /**
     * Flush all entries in provider, not supported for files
     *
     * @return boolean
     */
    public function flush()
    {
        return false;
    }

    /**
     * @see \Slab\Cache\Providers\Base::getInterface()
     */
    public function getInterface()
    {
        return false;
    }

    /**
     * Process the key to be filename safe
     *
     * @param string $key
     * @return string
     */
    protected function processKey($key)
    {
        $key = trim(strtolower($key));
        $key = preg_replace('#[^a-z0-9\.]#', '-', $key) . '~' . $this->keySuffix;

        return $key;
    }
}