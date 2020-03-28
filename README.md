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

## 扩展包要求

-   PHP >= 7.0

## 安装命令

```shell
$ composer require "iidestiny/laravel-filesystem-oss" -vvv
```

## 配置

1. 将服务提供者 `Iidestiny\LaravelFilesystemOss\OssStorageServiceProvider::class` 注册到 `config/app.php` 文件:

```php
'providers' => [
    // Other service providers...
    Iidestiny\LaravelFilesystemOss\OssStorageServiceProvider::class,
],
```

> Laravel 5.5+ 会自动注册服务提供者可过滤

2. 在 `config/filesystems.php` 配置文件中添加你的新驱动

```php
<?php

return [
   'disks' => [
        //...
        'oss' => [
            'driver' => 'oss',
            'root' => '', // 设置上传时根前缀
            'access_key' => env('OSS_ACCESS_KEY'),
            'secret_key' => env('OSS_SECRET_KEY'),
            'endpoint'   => env('OSS_ENDPOINT'), // 使用 ssl 这里设置如: https://oss-cn-beijing.aliyuncs.com
            'bucket'     => env('OSS_BUCKET'),
            'isCName'    => env('OSS_IS_CNAME', false), // 如果 isCname 为 false，endpoint 应配置 oss 提供的域名如：`oss-cn-beijing.aliyuncs.com`，否则为自定义域名，，cname 或 cdn 请自行到阿里 oss 后台配置并绑定 bucket
            // 如果有更多的 bucket 需要切换，就添加所有bucket，默认的 bucket 填写到上面，不要加到 buckets 中
            'buckets'=>[
                'test'=>[
                    'access_key' => env('OSS_ACCESS_KEY'),
                    'secret_key' => env('OSS_SECRET_KEY'),
                    'bucket'     => env('OSS_TEST_BUCKET'),
                    'endpoint'   => env('OSS_TEST_ENDPOINT'),
                    'isCName'    => env('OSS_TEST_IS_CNAME', false),
                ],
                //...
            ],
        ],
        //...
    ]
];
```

## 基本使用

```php
<?php

$disk = Storage::disk('oss');

// 上传
$disk->put('avatars/filename.jpg', $fileContents);

// 检查文件是否存在
$exists = $disk->has('file.jpg');

// 获取文件修改时间
$time = $disk->lastModified('file1.jpg');
$time = $disk->getTimestamp('file1.jpg');

// 拷贝文件
$disk->copy('old/file1.jpg', 'new/file1.jpg');

// 移动文件也可改名
$disk->move('old/file1.jpg', 'new/file1.jpg');

// 获取文件内容
$contents = $disk->read('folder/my_file.txt');
```

以上方法可在 [laravel-filesystem-doc](https://laravel.com/docs/5.5/filesystem) 查阅

## 进阶使用

```php
// 获取文件访问地址「公共读的 bucket 才生效」
$url = $disk->getUrl('folder/my_file.txt');

// 设置文件访问有效期「$timeout 为多少秒过期」「私有 bucket 才可看见效果」
$url = $disk->signUrl('cat.png', $timeout, ['x-oss-process' => 'image/circle,r_100']);

// 和 signurl 功能一样，区别在于 $expiration 是未来过期时间如：2019-05-05 17:50:32 时链接失效
$url = $disk->getTemporaryUrl('file.md', $expiration);

// 可切换其他 bucket「需要在 config 配置文件中配置 buckets」
$exists = $disk->bucket('test')->has('file.jpg');
```

## 获取官方完整 OSS 处理能力

阿里官方 SDK 可能处理了更多的事情，如果你想获取完整的功能可通过此插件获取，
然后你将拥有完整的 oss 处理能力

```php
// 获取完整处理能力
$kernel = $disk->kernel();

// 例如：防盗链功能
$refererConfig = new RefererConfig();
// 设置允许空Referer。
$refererConfig->setAllowEmptyReferer(true);
// 添加Referer白名单。Referer参数支持通配符星号（*）和问号（？）。
$refererConfig->addReferer("www.aliiyun.com");
$refererConfig->addReferer("www.aliiyuncs.com");

$kernel->putBucketReferer($bucket, $refererConfig);
```

> 更多功能请查看[官方 SDK 手册](https://help.aliyun.com/document_detail/32100.html?spm=a2c4g.11186623.6.1055.66b64a49hkcTHv)

## 前端 web 直传配置

oss 直传有三种方式，当前扩展包使用的是最完整的 [服务端签名直传并设置上传回调](https://help.aliyun.com/document_detail/31927.html?spm=a2c4g.11186623.2.10.5602668eApjlz3#concept-qp2-g4y-5db) 方式，扩展包只生成前端页面上传所需的签名参数，前端上传实现可参考 [官方文档中的实例](https://help.aliyun.com/document_detail/31927.html?spm=a2c4g.11186623.2.10.5602668eApjlz3#concept-qp2-g4y-5db) 或自行搜索

```php
/**
 * 1. 前缀如：'images/'
 * 2. 回调服务器 url
 * 3. 回调自定义参数，oss 回传应用服务器时会带上
 * 4. 当前直传配置链接有效期
 */
$config = $disk->signatureConfig($prefix = '/', $callBackUrl = '', $customData = [], $expire = 30);
```

## 直传回调验签

当设置了直传回调后，可以通过验签插件，验证并获取 oss 传回的数据 [文档](https://help.aliyun.com/document_detail/91771.html?spm=a2c4g.11186623.2.15.7ee07eaeexR7Y1#title-9t0-sge-pfr)

注意事项：
- 如果没有 Authorization 头信息导致验签失败需要先在 apache 或者 nginx 中设置 rewrite
- 以 apache 为例，修改 httpd.conf 在 DirectoryIndex index.php 这行下面增加「RewriteEngine On」「RewriteRule .* - [env=HTTP_AUTHORIZATION:%{HTTP:Authorization},last]」

```php
// 验签，就是如此简单
// $verify 验签结果，$data 回调数据
list($verify, $data) = $disk->verify();
// [$verify, $data] = $disk->verify(); // php 7.1 +

if (!$verify) {
    // 验证失败处理，此时 $data 为验签失败提示信息
}

// 注意一定要返回 json 格式的字符串，因为 oss 服务器只接收 json 格式，否则给前端报 CallbackFailed
return response()->json($data);
```

直传回调验收后返回给前端的数据「包括自定义参数」，例如

```json
{
    "filename": "user/15854050909488182.png",
    "size": "56039",
    "mimeType": "image/png",
    "height": "473",
    "width": "470",
    "custom_name": "zhangsan",
    "custom_age": "24"
}
```

> 这其实要看你回调通知方法具体怎么返回，如果直接按照文档给的方法返回是这个样子

## 前端直传组件分享「vue + element」

```html
<template>
  <div>
    <el-upload
      class="avatar-uploader"
      :action="uploadUrl"
      :on-success="handleSucess"
      :on-change="handleChange"
      :before-upload="handleBeforeUpload"
      :show-file-list="false"
      :data="data"
      :on-error="handleError"
      :file-list="files"
    >
      <img v-if="dialogImageUrl" :src="dialogImageUrl" class="avatar">
      <i v-else class="el-icon-plus avatar-uploader-icon" />
    </el-upload>
  </div>
</template>

<script>
import { getOssPolicy } from '@/api/oss' // 这里就是获取直传配置接口

export default {
  name: 'Upload',
  props: {
    url: {
      type: String,
      default: null
    }
  },
  data() {
    return {
      uploadUrl: '', // 上传提交地址
      data: {}, // 上传提交额外数据
      dialogImageUrl: '', // 预览图片
      files: [] // 上传的文件
    }
  },
  computed: {},
  created() {
    this.dialogImageUrl = this.url
  },
  methods: {
    handleChange(file, fileList) {
      console.log(file, fileList)
    },
    // 上传之前处理动作
    async handleBeforeUpload(file) {
      const fileName = this.makeRandomName(file.name)
      try {
        const response = await getOssPolicy()

        this.uploadUrl = response.host

        // 组装自定义参数「如果要自定义回传参数这段代码不能省略」
        if (Object.keys(response['callback-var']).length) {
          for (const [key, value] of Object.entries(response['callback-var'])) {
            this.data[key] = value
          }
        }

        this.data.policy = response.policy
        this.data.OSSAccessKeyId = response.accessid
        this.data.signature = response.signature
        this.data.host = response.host
        this.data.callback = response.callback
        this.data.key = response.dir + fileName
      } catch (error) {
        this.$message.error('获取上传配置失败')
        console.log(error)
      }
    },
    // 文件上传成功处理
    handleSucess(response, file, fileList) {
      const fileUrl = this.uploadUrl + this.data.key
      this.dialogImageUrl = fileUrl
      this.$emit('update:url', fileUrl)
      this.files.push({
        name: this.data.key,
        url: fileUrl
      })
    },
    // 上传失败处理
    handleError() {
      this.$message.error('上传失败')
    },
    // 随机名称
    makeRandomName(name) {
      const randomStr = Math.random().toString().substr(2, 4)
      const suffix = name.substr(name.lastIndexOf('.'))
      return Date.now() + randomStr + suffix
    }
  }

}
</script>

<style>
.avatar-uploader .el-upload {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
  }
  .avatar-uploader .el-upload:hover {
    border-color: #409EFF;
  }
  .avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 150px;
    height: 150px;
    line-height: 150px;
    text-align: center;
  }
  .avatar {
    width: 150px;
    height: 150px;
    display: block;
  }
</style>

```

## 依赖的核心包

-   [iidestiny/flysystem-oss](https://github.com/iiDestiny/flysystem-oss)

## 参考

-   [overtrue/flysystem-qiniu](https://github.com/overtrue/flysystem-qiniu)

## License

[![LICENSE](https://img.shields.io/badge/license-Anti%20996-blue.svg)](https://github.com/996icu/996.ICU/blob/master/LICENSE)
