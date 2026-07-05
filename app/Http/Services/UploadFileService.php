<?php

namespace App\Http\Services;

use App\Http\Requests\UploadFileRequest;
use App\Models\UploadFile;

class UploadFileService
{
    public function formatSize(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    public function uploadFile(UploadFileRequest $file): UploadFile
    {
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('temp', $filename, 'public');
        $rawSize = $file->getSize();
        $formattedSize = $this->formatSize($rawSize);

        $uploadFile = new UploadFile();
        $uploadFile->name = $filename;

        $uploadFile->path = asset('storage/' . $path);
        $uploadFile->type = $file->getClientMimeType();

        $uploadFile->size = $formattedSize;

        $uploadFile->save();

        return $uploadFile;
    }
}
