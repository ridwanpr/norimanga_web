<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class BucketManager
{
    private const BUCKET_PREFIX = 's';
    private const BUCKET_START = 3;
    private const BUCKET_END = 7;
    private const BUCKET_THRESHOLD_GB = 24;
    private const CACHE_KEY = 'current_bucket';
    private const CACHE_DURATION_MINUTES = 60;

    public function getCurrentBucket(): string
    {
        $currentBucket = Cache::get(self::CACHE_KEY);

        if (!$currentBucket) {
            $currentBucket = $this->findFirstAvailableBucket();
            Cache::put(self::CACHE_KEY, $currentBucket, now()->addMinutes(self::CACHE_DURATION_MINUTES));
        }

        // Double-check bucket capacity
        if ($this->getBucketUsageGB($currentBucket) >= self::BUCKET_THRESHOLD_GB) {
            $currentBucket = $this->findFirstAvailableBucket();
            Cache::put(self::CACHE_KEY, $currentBucket, now()->addMinutes(self::CACHE_DURATION_MINUTES));
        }

        return $currentBucket;
    }

    public function storeFile(string $path, $contents, array $options = []): array
    {
        $bucket = $this->getCurrentBucket();
        
        try {
            // Store the file
            Storage::disk($bucket)->put($path, $contents, $options);

            return [
                'bucket' => $bucket,
                'url' => Storage::disk($bucket)->url($path)
            ];
        } catch (\Exception $e) {
            Log::error("Failed to store file in bucket {$bucket}: " . $e->getMessage());
            throw $e;
        }
    }

    private function findFirstAvailableBucket(): string
    {
        for ($i = self::BUCKET_START; $i <= self::BUCKET_END; $i++) {
            $bucket = self::BUCKET_PREFIX . $i;

            if ($this->getBucketUsageGB($bucket) < self::BUCKET_THRESHOLD_GB) {
                return $bucket;
            }
        }

        Log::error('All buckets are near capacity!');
        throw new \RuntimeException('All storage buckets are near capacity');
    }

    private function getBucketUsageGB(string $bucket): float
    {
        try {
            $files = Storage::disk($bucket)->allFiles();
            $totalBytes = 0;

            foreach ($files as $file) {
                $totalBytes += Storage::disk($bucket)->size($file);
            }

            return $totalBytes / (1024 * 1024 * 1024); // Convert to GB
        } catch (\Exception $e) {
            Log::error("Failed to get bucket usage for {$bucket}: " . $e->getMessage());
            return 0;
        }
    }

    public function deleteFile(string $bucket, string $path): bool
    {
        try {
            return Storage::disk($bucket)->delete($path);
        } catch (\Exception $e) {
            Log::error("Failed to delete file from bucket {$bucket}: " . $e->getMessage());
            return false;
        }
    }
}
