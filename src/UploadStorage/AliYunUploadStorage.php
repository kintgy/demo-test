<?php

namespace App\UploadStorage;

use OSS\OssClient;

class AliYunUploadStorage implements UploadStorage
{
    private $accessKey;

    private $accessSecret;

    private $endPoint;

    private $bucket;

    private $ossPath;

    /**
     * AliYunUploadStorage constructor.
     * @param array $aliYunConfig
     *                 string accessKey
     *                 string accessSecret
     *                 string endPoint
     *                 string bucket
     *                 string ossPath
     */
    public function __construct(array $aliYunConfig)
    {
        $this->accessKey = $aliYunConfig['access_key'];
        $this->accessSecret = $aliYunConfig['access_secret'];
        $this->endPoint = $aliYunConfig['endpoint'];
        $this->bucket = $aliYunConfig['bucket'];
        $this->ossPath = $aliYunConfig['oss_path'];
    }

    public function upload($filePath, $fileName)
    {
        if (!file_exists($filePath)) {
            throw new \Exception('上传文件不存在');
        }

        try {
            $oss = new OssClient($this->accessKey, $this->accessSecret, $this->endPoint);
            $oss->uploadFile('kintgy', $this->ossPath . DIRECTORY_SEPARATOR . $fileName, $filePath);
        } catch (\Exception $e) {
            throw new \Exception('上传文件异常: ' . $e->getMessage());
        }
    }
}