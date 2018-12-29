<?php

namespace Iidestiny\LaravelFilesystemOss;

use Iidestiny\Flysystem\Oss\OssAdapter;
use Iidestiny\Flysystem\Oss\Plugins\FileUrl;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;

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
