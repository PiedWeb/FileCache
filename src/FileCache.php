<?php
namespace PiedWeb\FileCache;

class FileCache
{
    /**
     * Prefix your cache files
     * @var string
     */
    protected $prefix;

    /**
     * Folder where your cache files are stored
     * @var string
     */
    protected $folder;

    /**
     * Constructor
     *
     * @param string $folder Folder containing cache files. Default /tmp
     * @param string $prefix Prefix for the cache files. Default empty.
     */
    public function __construct(string $folder = '/tmp', string $prefix = '')
    {
        $this->setCacheFolder($folder);
        $this->setPrefix($prefix);
    }

    /**
     * Instanciator
     *
     * @param string $folder Folder containing cache files. Default /tmp
     * @param string $prefix Prefix for the cache files. Default empty.
     *
     * return self
     */
    public static function instance(string $folder = '/tmp', string $prefix = '')
    {
        $class = get_called_class();
        return new  $class($folder, $prefix);
    }

    /**
     * Chainable prefix setter
     *
     * @param string $prefix
     *
     * @return self
     */
    protected function setPrefix(string $prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * Set the cache folder (chainable folder setter)
     *
     * @param string $folder
     *
     * @return self
     */
    protected function setCacheFolder(string $folder)
    {
        $this->folder = $folder;

        return $this;
    }

    /**
     * Get cache file path
     *
     * @param mixed $key
     *
     * @return string
     */
    public function getCacheFilePath($key)
    {
        return $this->folder.'/'.$this->prefix.sha1($key);
    }

    /**
     * Return your cache data else create and return data
     *
     * @param string $key    String wich permit to identify your cache file
     * @param int    $maxAge Time the cache is valid. Default 86400 (1 day).
     * @param mixed  $data   It can be a function wich generate data to cache or a variable wich will be directly stored
     *
     * @return mixed Return your $data or the esponse from your function (or it cache)
     */
    public function getElseCreate($key, int $maxAge, $data)
    {
        $cachedData = $this->get($key, $maxAge);

        if ($cachedData === false) {
            $cachedData = is_callable($data) ? call_user_func($data) : $data;
            $this->set($key, $cachedData);
        }

        return $cachedData;
    }

    /**
     * Get your cached data if exist else return false
     *
     * @param string $key    String wich permit to identify your cache file
     * @param int    $maxAge Time the cache is valid. Default 86400 (1 day). 0 = always valid
     *
     * @return mixed Return FALSE if cache not found or not valid (BUT WHAT IF WE STORE A BOOL EQUAL TO FALSE ?!)
     */
    public function get($key, int $maxAge = 86400)
    {
        $cacheFile = $this->getCacheFilePath($key);
        if ($this->isCacheFileValid($cacheFile, $maxAge)) {
            return unserialize(file_get_contents($this->getCacheFilePath($key)));
        }

        return false;
    }

    /**
     * Set your data in cache
     *
     * @param string $key  String wich permit to identify your cache file
     * @param mixed  $data Variable wich will be directly stored
     *
     * @return self
     */
    public function set($key, $data)
    {
        file_put_contents($this->getCacheFilePath($key), serialize($data));

        return $this;
    }

    /**
     * Cache is valid ?
     *
     * @param string $key    String wich permit to identify your cache file
     * @param int    $maxAge Time the cache is valid. Default 86400 (1 day).
     *
     * @return bool
     */
    public function isCacheValid($key, int $maxAge)
    {
        $cacheFile = $this->getCacheFilePath($key);

        return $this->isCacheFileValid($cacheFile, $maxAge);
    }

    /**
     * Cache File is valid ?
     *
     * @param string $cacheFile Cache file path
     * @param int    $maxAge    Time the cache is valid. Default 86400 (1 day).
     *
     * @return bool
     */
    protected function isCacheFileValid($cacheFile, $maxAge)
    {
        $expire = time() - $maxAge;

        return !file_exists($cacheFile) || (filemtime($cacheFile) <= $expire && $maxAge !== 0)  ? false : true;
    }

    /**
     * @return bool (same as unlink php function)
     */
    public function deleteCacheFile($key)
    {
        return unlink($this->getCacheFilePath($key));
    }

    /**
     * Delete all cache files with the $prefix
     *
     * @return int Number of deleted files
     *
     * @throw \Exception If the prefix is empty
     */
    public function deleteCacheFilesByPrefix()
    {
        if (empty($this->prefix)) {
            throw new Exception('FileCache::Prefix is empty : Can\'t delete cache files by prefix.');
        }

        $deletedFilesCounter = 0;
        $files = glob($this->folder.'/'.$this->prefix.'*', GLOB_NOSORT);
        foreach ($files as $file) {
            unlink($file);
            ++$deletedFilesCounter;
        }

        return $deletedFilesCounter;
    }
}
