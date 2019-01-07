# File Cache

[![Latest Version](https://img.shields.io/github/tag/PiedWeb/FileCache.svg?style=flat&label=release)](https://github.com/PiedWeb/FileCache/tags)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat)](https://github.com/PiedWeb/FileCache/LICENSE)
[![Build Status](https://img.shields.io/travis/PiedWeb/FileCache/master.svg?style=flat)](https://travis-ci.org/PiedWeb/FileCache)
[![Quality Score](https://img.shields.io/scrutinizer/g/PiedWeb/FileCache.svg?style=flat)](https://scrutinizer-ci.com/g/PiedWeb/FileCache)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/PiedWeb/FileCache.svg?style=flat)](https://scrutinizer-ci.com/g/PiedWeb/FileCache/code-structure)
[![Total Downloads](https://img.shields.io/packagist/dt/piedweb/file-cache.svg?style=flat)](https://packagist.org/packages/piedweb/file-cache)

Simple file cache library. Tested and approved. Intuitive and documented (inline and in this Readme).

## Install

Via [Packagist](https://img.shields.io/packagist/dt/piedweb/file-cache.svg?style=flat)

``` bash
$ composer require piedweb/file-cache
```

## Usage

``` php
use PiedWeb\FileCache\FileCache;

$key = 'data-2032'; // string to identify the cached data
$maxAge = 3600;     // 1 hour
$folder = './cache';
$prefix = 'tmp_';
$data = 'example data, but can be an int or an array which will be serialized'

/** Create a cache file **/
FileCache::instance()->setPrefix($folder) // Useful when you want to delete every cached data of the same type
FileCache::instance()->setCacheFolder($pregix)


FileCache::instance($folder, $prefix)->set($key, 'My string to set in a cache || But it could be an array or an object...');
FileCache::instance($folder, $prefix)->get($key, $maxAge);
FileCache::instance($folder, $prefix)->get($key, 0);  // Always valid. No expiration
FileCache::instance($folder, $prefix)->getElseCreate($key , $maxAge, function() { return ['My first data in cache']; });

/** Delete all cache files with the prefix `prfixForCacheFiles_` **/
FileCache::instance($folder, $prefix)->deleteCacheFilesByPrefix();
```

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing](https://dev.piedweb.com/contributing)

## Credits

- [PiedWeb](https://piedweb.com)
- [All Contributors](https://github.com/PiedWeb/:package_skake/graphs/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

[![Latest Version](https://img.shields.io/github/tag/PiedWeb/FileCache.svg?style=flat&label=release)](https://github.com/PiedWeb/FileCache/tags)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat)](https://github.com/PiedWeb/FileCache/LICENSE)
[![Build Status](https://img.shields.io/travis/PiedWeb/FileCache/master.svg?style=flat)](https://travis-ci.org/PiedWeb/FileCache)
[![Quality Score](https://img.shields.io/scrutinizer/g/PiedWeb/FileCache.svg?style=flat)](https://scrutinizer-ci.com/g/PiedWeb/FileCache)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/PiedWeb/FileCache.svg?style=flat)](https://scrutinizer-ci.com/g/PiedWeb/FileCache/code-structure)
[![Total Downloads](https://img.shields.io/packagist/dt/piedweb/file-cache.svg?style=flat)](https://packagist.org/packages/piedweb/file-cache)
