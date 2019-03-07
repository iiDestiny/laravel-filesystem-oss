<h1 align="center">laravel filesystem oss</h1>

<p align="center">
<a href="https://www.aliyun.com/product/oss">AliOss</a> storage for Laravel based on <a href="https://github.com/iiDestiny/flysystem-oss">iidestiny/flysystem-oss</a>.
</p>

<p align="center">
<a href="https://github.com/iiDestiny/flysystem-oss"><img src="https://travis-ci.org/iiDestiny/flysystem-oss.svg?branch=master"></a>
<a href="https://github.com/iiDestiny/flysystem-oss"><img src="https://github.styleci.io/repos/163501119/shield"></a>
<a href="https://github.com/iiDestiny/laravel-filesystem-oss"><img src="https://poser.pugx.org/iidestiny/laravel-filesystem-oss/v/stable"></a>
<a href="https://github.com/iiDestiny/laravel-filesystem-oss"><img src="https://poser.pugx.org/iidestiny/laravel-filesystem-oss/downloads"></a>
<a href="https://github.com/iiDestiny/laravel-filesystem-oss"><img src="https://poser.pugx.org/iidestiny/laravel-filesystem-oss/v/unstable"></a>
<a href="https://scrutinizer-ci.com/g/iiDestiny/flysystem-oss/?branch=master"><img src="https://scrutinizer-ci.com/g/iiDestiny/flysystem-oss/badges/quality-score.png?b=master"></a>
<a href="https://github.com/iiDestiny/laravel-filesystem-oss"><img src="https://badges.frapsoft.com/os/v1/open-source.svg?v=103"></a>
<a href="https://github.com/iiDestiny/laravel-filesystem-oss"><img src="https://poser.pugx.org/iidestiny/laravel-filesystem-oss/license"></a>
</p>

## Requirement

-   PHP >= 7.0

## Installing

```shell
$ composer require "iidestiny/laravel-filesystem-oss" -vvv
```

## Configuration

1. After installing the library, register the `Iidestiny\LaravelFilesystemOss\OssStorageServiceProvider::class` in your `config/app.php` file:

```php
'providers' => [
    // Other service providers...
    Iidestiny\LaravelFilesystemOss\OssStorageServiceProvider::class,
],
```

> Laravel 5.5+ skip

2. Add a new disk to your `config/filesystems.php` config:

```php
<?php

return [
   'disks' => [
        //...
        'oss' => [
            'driver' => 'oss',
            'access_key' => env('OSS_ACCESS_KEY'),
            'secret_key' => env('OSS_SECRET_KEY'),
            'endpoint'   => env('OSS_ENDPOINT'),
            'bucket'     => env('OSS_BUCKET'),
            'isCName'    => env('OSS_IS_CNAME', false), // // 如果 isCname 为 false，endpoint 应配置 oss 提供的域名如：`oss-cn-beijing.aliyuncs.com`，否则为自定义域名，，cname 或 cdn 请自行到阿里 oss 后台配置并绑定 bucket
        ],
        //...
    ]
];
```

## Usage

```php
<?php

$disk = Storage::disk('oss');

// create a file
$disk->put('avatars/filename.jpg', $fileContents);

// check if a file exists
$exists = $disk->has('file.jpg');

// get timestamp
$time = $disk->lastModified('file1.jpg');
$time = $disk->getTimestamp('file1.jpg');

// copy a file
$disk->copy('old/file1.jpg', 'new/file1.jpg');

// move a file
$disk->move('old/file1.jpg', 'new/file1.jpg');

// get file contents
$contents = $disk->read('folder/my_file.txt');

// get file url
$url = $disk->getUrl('folder/my_file.txt');

// file access period
$url = $disk->signUrl('file.md', $timeout);
```

See more methods [laravel-filesystem-doc](https://laravel.com/docs/5.5/filesystem)

## depend

-   [iidestiny/flysystem-oss](https://github.com/iiDestiny/flysystem-oss)

## reference

-   [overtrue/flysystem-qiniu](https://github.com/overtrue/flysystem-qiniu)

## License

MIT
