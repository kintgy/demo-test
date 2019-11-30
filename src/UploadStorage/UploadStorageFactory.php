<?php

namespace App\UploadStorage;

class UploadStorageFactory
{
    private $configs;

    public function __construct(array $options)
    {
        $this->configs = $options;
    }

    public function create($storageWay)
    {
        if ($storageWay === 'aliYun') {
            return new AliYunUploadStorage($this->configs['aliYun']);
        }

    }
}