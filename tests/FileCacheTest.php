<?php
namespace PiedWeb\FileCache\Test;

use PiedWeb\FileCache\FileCache;

class FileCacheTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test caching data with file prefixed
     */
    function testCachingWithPrefix()
    {
        $folder = '/tmp';
        $prefix = 'newprefix_';
        $key = 'test-cache';
        $FileCache = new FileCache($folder, $prefix);
        $FileCache->getElseCreate($key, 3600, [$this, 'anArray']);

        $this->assertTrue(strpos($FileCache->getCacheFilePath($key), $folder.'/'.$prefix) === 0);
        $FileCache->deleteCacheFile($key);
    }

    /**
     * Test caching a file
     */
    public function testCaching()
    {
        $key = 'test-cache';

        $FileCache = FileCache::instance('/tmp', 'tmp');
        $data = $FileCache->getElseCreate($key, 3600, [$this, 'anArray']);
        $this->assertTrue(file_exists($FileCache->getCacheFilePath($key)));

        $this->assertTrue(empty(array_diff($this->anArray(), $data)));

        sleep(2);
        $this->assertTrue(!$FileCache->isCacheValid($key, 2));

        $FileCache->deleteCacheFile($key);
    }

    /**
     * Test deleting file by prefix
     */
    public function testDeletingFilesByPrefix()
    {
        $FileCache = FileCache::instance('/tmp', 'prefix_');

        for ($i=1;$i<=10;++$i) {
            $key = 'myfilecache'.$i;
            $data = $FileCache->getElseCreate($key, 3600, [$this, 'anArray']);
        }

        $this->assertTrue($FileCache->deleteCacheFilesByPrefix() == $i-1);
    }

    public function anArray()
    {
        return ['tagada' => 'tsoin', 'tsoin'];
    }

    public function testCacheAlwaysValid()
    {
        $key = 'my-cache';
        $data = 'Youhouhouhouhouhou ';

        $FileCache = FileCache::instance('/tmp', 'always');
        $FileCache->set($key, $data);
        $dataFromCache = $FileCache->get($key, 0);

        $this->assertTrue($data == $dataFromCache);
        $FileCache->deleteCacheFile($key);
    }
}
