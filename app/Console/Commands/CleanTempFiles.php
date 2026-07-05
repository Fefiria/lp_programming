<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\UploadFile;

class CleanTempFiles extends Command
{
    protected $signature = 'app:clean-temp-files';

    protected $description = 'Clean temporary uploaded files that are older than a specific time';

    public function handle()
    {
        $directory = 'temp';
        $files = Storage::disk('public')->files($directory);
        $now = Carbon::now();
        $deletedCount = 0;

        foreach ($files as $file) {
            $lastModified = Carbon::createFromTimestamp(Storage::disk('public')->lastModified($file));

            $this->info("Checking file: " . basename($file));
            $this->info("Last Modified: " . $lastModified->toDateTimeString());
            $this->info("Now: " . $now->toDateTimeString());
            $this->info("Diff in seconds: " . $now->diffInSeconds($lastModified));

            // Untuk pengujian diset 10 detik. Jika production, ganti menjadi > 1440 (24 jam)
            // if ($lastModified->copy()->addMinutes(1440)->isPast()) { // 24 hours
            if ($lastModified->copy()->addSeconds(1440)->isPast()) {
                $filename = basename($file);

                UploadFile::where('name', $filename)->delete();
                Storage::disk('public')->delete($file);

                $deletedCount++;
                $this->info("Deleted: $filename");
            } else {
                $this->info("File is not older than 10 seconds.");
            }
        }

        $this->info("Pembersihan selesai. Total file dihapus: $deletedCount");
    }
}
