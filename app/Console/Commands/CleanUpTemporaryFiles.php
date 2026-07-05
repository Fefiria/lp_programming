<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CleanUpTemporaryFiles extends Command
{
    protected $signature = 'app:clean-temp-files';
    protected $description = 'Clean up temporary uploaded files older than 24 hours';

    public function handle()
    {
        $this->info('Starting temporary files cleanup...');
        $directory = 'temp';

        $files = Storage::disk('public')->files($directory);
        $now = Carbon::now();

        foreach ($files as $file) {
            $lastModified = Carbon::createFromTimestamp(Storage::disk('public')->lastModified($file));

            if ($now->diffInMinutes($lastModified) > 1440) {
                Storage::disk('public')->delete($file);
                $this->info("Deleted: {$file}");
            }
        }

        $this->info('Cleanup finished successfully.');
    }
}
