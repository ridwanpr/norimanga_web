<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DatabaseBackupController extends Controller
{
    /**
     * Handle the backup request triggered by cronjob.org
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function backup(Request $request)
    {
        if (!$this->validateRequest($request)) {
            Log::warning('Unauthorized backup attempt');
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            // Create filename with timestamp
            $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $tempPath = storage_path('app/temp/' . $filename);

            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }

            // Get database credentials
            $database = env('DB_DATABASE');
            $user = env('DB_USERNAME');
            $password = env('DB_PASSWORD');
            $host = env('DB_HOST');

            // mysqldump command 
            $command = "mysqldump --user=$user --password=$password --host=$host $database > $tempPath";

            // Logging the command for debugging purposes
            Log::info('Running mysqldump command: ' . $command);

            $output = null;
            $returnVar = null;
            exec($command, $output, $returnVar);

            // Check if the command was successful
            if ($returnVar !== 0) {
                Log::error('mysqldump failed with return code ' . $returnVar);
                Log::error('mysqldump output: ' . implode("\n", $output));
                throw new \Exception('Database backup failed. mysqldump error.');
            }

            // Check if the file is empty after mysqldump
            if (filesize($tempPath) == 0) {
                Log::error('Backup file is empty: ' . $tempPath);
                throw new \Exception('Database backup resulted in an empty file.');
            }

            // Upload the dump to R2
            $s3Path = 'backups/db/' . $filename;
            Storage::disk('r2')->put($s3Path, file_get_contents($tempPath));

            // Clean up the temporary file
            unlink($tempPath);

            Log::info('Database backup completed and uploaded to S3: ' . $s3Path);
            return response()->json(['success' => true, 'message' => 'Backup completed', 'file' => $s3Path]);
        } catch (\Exception $e) {
            Log::error('Database backup failed: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Validate the request by checking the secret key
     *
     * @param Request $request
     * @return bool
     */
    private function validateRequest(Request $request)
    {
        $validSecretKey = 'cahstel';

        // First try header-based authentication
        $providedKey = $request->header('X-Backup-Secret');

        // Then try query parameter if header wasn't provided
        if (empty($providedKey)) {
            $providedKey = $request->query('secret');
        }

        return !empty($validSecretKey) && !empty($providedKey) && hash_equals($validSecretKey, $providedKey);
    }
}
