# laravel filesystem oss

[AliOss](https://www.aliyun.com/product/oss) storage for Laravel based on [iidestiny/flysystem-oss](https://github.com/iiDestiny/flysystem-oss).

[![Latest Stable Version](https://poser.pugx.org/iidestiny/laravel-filesystem-oss/v/stable)](https://packagist.org/packages/iidestiny/laravel-filesystem-oss)
[![Total Downloads](https://poser.pugx.org/iidestiny/laravel-filesystem-oss/downloads)](https://packagist.org/packages/iidestiny/laravel-filesystem-oss)
[![Latest Unstable Version](https://poser.pugx.org/iidestiny/laravel-filesystem-oss/v/unstable)](https://packagist.org/packages/iidestiny/laravel-filesystem-oss)
[![License](https://poser.pugx.org/iidestiny/laravel-filesystem-oss/license)](https://packagist.org/packages/iidestiny/laravel-filesystem-oss)


## Requirement

- PHP >= 7.0

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

- [iidestiny/flysystem-oss](https://github.com/iiDestiny/flysystem-oss)

## reference

- [overtrue/flysystem-qiniu](https://github.com/overtrue/flysystem-qiniu)

## License

MIT