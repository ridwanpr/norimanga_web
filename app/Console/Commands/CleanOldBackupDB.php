<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CleanOldBackupDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:clean-r2';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete database backups on Cloudflare R2 older than one week';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $appName = Config::get('app.name');
        $backupPath = "{$appName}";

        $diskR2 = Storage::disk('r2');
        $files = $diskR2->files($backupPath);
        $thresholdDate = Carbon::now()->subWeek();

        if (empty($files)) {
            $this->info('No backup files found in Cloudflare R2.');
            Log::info('Cloudflare R2 cleanup: No backup files found.');
            return;
        }

        $deletedFiles = 0;

        foreach ($files as $file) {
            $fileTimestamp = Carbon::createFromTimestamp($diskR2->lastModified($file));

            if ($fileTimestamp->lt($thresholdDate)) {
                $diskR2->delete($file);
                $deletedFiles++;
                Log::info("Deleted old backup file: {$file}");
            }
        }

        if ($deletedFiles > 0) {
            $this->info("Deleted {$deletedFiles} old backup files from Cloudflare R2.");
            Log::info("Cloudflare R2 cleanup: Deleted {$deletedFiles} old backup files.");
        } else {
            $this->info('No old backup files found to delete.');
            Log::info('Cloudflare R2 cleanup: No old files to delete.');
        }
    }
}
