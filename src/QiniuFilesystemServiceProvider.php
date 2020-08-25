<?php

namespace wl1524520\QiniuStorage;

use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use wl1524520\QiniuStorage\Plugins\DownloadUrl;
use wl1524520\QiniuStorage\Plugins\Fetch;
use wl1524520\QiniuStorage\Plugins\ImageExif;
use wl1524520\QiniuStorage\Plugins\ImageInfo;
use wl1524520\QiniuStorage\Plugins\AvInfo;
use wl1524520\QiniuStorage\Plugins\ImagePreviewUrl;
use wl1524520\QiniuStorage\Plugins\LastReturn;
use wl1524520\QiniuStorage\Plugins\PersistentFop;
use wl1524520\QiniuStorage\Plugins\PersistentStatus;
use wl1524520\QiniuStorage\Plugins\PrivateDownloadUrl;
use wl1524520\QiniuStorage\Plugins\Qetag;
use wl1524520\QiniuStorage\Plugins\UploadToken;
use wl1524520\QiniuStorage\Plugins\PrivateImagePreviewUrl;
use wl1524520\QiniuStorage\Plugins\VerifyCallback;
use wl1524520\QiniuStorage\Plugins\WithUploadToken;

class QiniuFilesystemServiceProvider extends ServiceProvider
{

    public function boot()
    {
        Storage::extend(
            'qiniu',
            function ($app, $config) {
                if (isset($config['domains'])) {
                    $domains = $config['domains'];
                } else {
                    $domains = [
                        'default' => $config['domain'],
                        'https'   => null,
                        'custom'  => null
                    ];
                }
                $qiniu_adapter = new QiniuAdapter(
                    $config['access_key'],
                    $config['secret_key'],
                    $config['bucket'],
                    $domains,
                    isset($config['default_domain_type']) ? $config['default_domain_type'] : 'default',
                    isset($config['notify_url']) ? $config['notify_url'] : null,
                    isset($config['access']) ? $config['access'] : 'public',
                    isset($config['hotlink_prevention_key']) ? $config['hotlink_prevention_key'] : null
                );
                $file_system = new Filesystem($qiniu_adapter);
                $file_system->addPlugin(new PrivateDownloadUrl());
                $file_system->addPlugin(new DownloadUrl());
                $file_system->addPlugin(new AvInfo());
                $file_system->addPlugin(new ImageInfo());
                $file_system->addPlugin(new ImageExif());
                $file_system->addPlugin(new ImagePreviewUrl());
                $file_system->addPlugin(new PersistentFop());
                $file_system->addPlugin(new PersistentStatus());
                $file_system->addPlugin(new UploadToken());
                $file_system->addPlugin(new PrivateImagePreviewUrl());
                $file_system->addPlugin(new VerifyCallback());
                $file_system->addPlugin(new Fetch());
                $file_system->addPlugin(new Qetag());
                $file_system->addPlugin(new WithUploadToken());
                $file_system->addPlugin(new LastReturn());

                return $file_system;
            }
        );
    }

    public function register()
    {
        //
    }
}
