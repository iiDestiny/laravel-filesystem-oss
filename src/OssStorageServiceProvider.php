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
use Iidestiny\Flysystem\Oss\Plugins\FileUrl;
use Iidestiny\Flysystem\Oss\Plugins\SignUrl;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
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
        Storage::extend('oss', function ($app, $config) {
            $adapter = new OssAdapter(
                $config['access_key'], $config['secret_key'],
                $config['endpoint'], $config['bucket'], $config['isCName']
            );

            $filesystem = new Filesystem($adapter);

            $filesystem->addPlugin(new FileUrl());
            $filesystem->addPlugin(new SignUrl());

            return $filesystem;
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
