<?php

namespace App\UploadStorage;

interface UploadStorage
{
    public function upload($filePath, $fileName);
}