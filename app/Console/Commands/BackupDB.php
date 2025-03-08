<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class BackupDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:upload-r2';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily backup database to Cloudflare R2';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $appName = Config::get('app.name');
        $backupPath = "{$appName}";

        $diskLocal = Storage::disk('local');
        $diskR2 = Storage::disk('r2');

        $files = $diskLocal->files($backupPath);

        if (empty($files)) {
            $this->error('No backup files found.');
            Log::warning('Backup upload failed: No backup files found in ' . $backupPath);
            return;
        }

        $latestBackup = collect($files)->sortDesc()->first();

        if (!$latestBackup) {
            $this->error('No backup file found.');
            Log::warning('Backup upload failed: No backup file available in ' . $backupPath);
            return;
        }

        $this->info("Uploading {$latestBackup} to Cloudflare R2...");

        try {
            $diskR2->put($latestBackup, $diskLocal->get($latestBackup));
            $this->info('Backup successfully uploaded to Cloudflare R2.');
            Log::info("Backup file {$latestBackup} successfully uploaded to Cloudflare R2.");
        } catch (\Exception $e) {
            $this->error('Backup upload failed.');
            Log::error('Backup upload failed: ' . $e->getMessage());
            Log::error($e);
        }
    }
}
