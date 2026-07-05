<?php

namespace App\Http\Services;

use Illuminate\Http\UploadedFile;
use App\Models\UploadFile;
use Illuminate\Support\Facades\Storage;

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

    public function uploadFile(UploadedFile $file): UploadFile
    {
        $filename = time() . '_' . $file->getClientOriginalName();
        $mimeType = $file->getClientMimeType();
        $destinationPath = storage_path('app/public/temp/');

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $targetFile = $destinationPath . $filename;

        if (str_contains($mimeType, 'image')) {
            $sourcePath = $file->getRealPath();
            $image = @imagecreatefromstring(file_get_contents($sourcePath));
            if ($image) {
                imagejpeg($image, $targetFile, 50);
                imagedestroy($image);
                $rawSize = filesize($targetFile);
            } else {
                $file->storeAs('temp', $filename, 'public');
                $rawSize = $file->getSize();
            }
        } elseif ($mimeType === 'application/pdf') {
            $sourcePath = $file->getRealPath();
            $command = "gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/screen -dNOPAUSE -dQUIET -dBATCH -sOutputFile=" . escapeshellarg($targetFile) . " " . escapeshellarg($sourcePath);

            shell_exec($command);

            if (file_exists($targetFile)) {
                $rawSize = filesize($targetFile);
            } else {
                $file->storeAs('temp', $filename, 'public');
                $rawSize = $file->getSize();
            }
        } else {
            $file->storeAs('temp', $filename, 'public');
            $rawSize = $file->getSize();
        }

        $uploadFile = new UploadFile();
        $uploadFile->name = $filename;
        $uploadFile->path = asset('storage/temp/' . $filename);
        $uploadFile->type = $mimeType;
        $uploadFile->size = $rawSize;
        $uploadFile->save();

        return $uploadFile;
    }


    public function moveFileToPrimary(string $tempFilename, string $primaryDirectory)
    {
        $tempPath = 'temp/' . $tempFilename;
        $primaryPath = $primaryDirectory . '/' . $tempFilename;

        if (Storage::disk('public')->exists($tempPath)) {
            Storage::disk('public')->move($tempPath, $primaryPath);

            $uploadFile = UploadFile::where('name', $tempFilename)->first();
            if ($uploadFile) {
                $uploadFile->path = asset('storage/' . $primaryPath);
                $uploadFile->save();
            }

            return $primaryPath;
        }

        return false;
    }
}
