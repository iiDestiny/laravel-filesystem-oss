<?php

/*
 * This file is part of the iidestiny/flysystem-oss.
 *
 * (c) iidestiny <iidestiny@vip.qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Iidestiny\LaravelFilesystemOss;

use Iidestiny\Flysystem\Oss\OssAdapter;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;

/**
 * Class OssStorageServiceProvider
 *
 * @author iidestiny <iidestiny@vip.qq.com>
 */
class OssStorageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        app('filesystem')->extend('oss', function ($app, $config) {
            $root = $config['root'] ?? null;
            $buckets = $config['buckets'] ?? [];

            $adapter = new OssAdapter(
                $config['access_key'],
                $config['secret_key'],
                $config['endpoint'],
                $config['bucket'],
                $config['isCName'],
                $root,
                $buckets,
                ...($config['clientParams'] ?? []),
            );

            $adapter->setCdnUrl($config['url'] ?? null);

            return new FilesystemAdapter(new Filesystem($adapter), $adapter, $config);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
