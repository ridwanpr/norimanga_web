<?php

namespace App\Services;

use App\Models\BucketUsage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class BucketManager
{
    private const BUCKET_PREFIX = 's';
    private const BUCKET_START = 3;
    private const BUCKET_END = 5;
    private const BUCKET_THRESHOLD_GB = 16;
    private const CACHE_KEY = 'current_bucket';
    private const CACHE_DURATION_MINUTES = 60;

    public function getCurrentBucket(): string
    {
        $currentBucket = Cache::get(self::CACHE_KEY);

        if (!$currentBucket) {
            $currentBucket = $this->findFirstAvailableBucket();
            Cache::put(self::CACHE_KEY, $currentBucket, now()->addMinutes(self::CACHE_DURATION_MINUTES));
            // Log::info('New currentBucket assigned:', ['currentBucket' => $currentBucket]);
        }

        if ($this->getBucketUsageGB($currentBucket) >= self::BUCKET_THRESHOLD_GB) {
            $currentBucket = $this->findFirstAvailableBucket();
            Cache::put(self::CACHE_KEY, $currentBucket, now()->addMinutes(self::CACHE_DURATION_MINUTES));
            // Log::info('Updated currentBucket due to threshold:', ['currentBucket' => $currentBucket]);
        }

        return $currentBucket;
    }

    public function storeFile(string $path, $contents, string $bucket, array $options = []): array
    {
        try {
            Storage::disk($bucket)->put($path, $contents, $options);
            $url = Storage::disk($bucket)->url($path);
    
            return [
                'bucket' => $bucket,
                'url' => $url,
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
            $usageGB = $this->getBucketUsageGB($bucket);

            if ($usageGB < self::BUCKET_THRESHOLD_GB) {
                return $bucket;
            }
        }
        Log::error('All buckets are near capacity!');
        throw new \RuntimeException('All storage buckets are near capacity');
    }

    private function getBucketUsageGB(string $bucket): float
    {
        $usage = BucketUsage::where('bucket_name', $bucket)->value('total_bytes');
        $usageGB = $usage ? $usage / (1024 * 1024 * 1024) : 0;
        return $usageGB;
    }

    public function deleteFile(string $bucket, string $path): bool
    {
        try {
            $size = Storage::disk($bucket)->size($path);

            $deleted = Storage::disk($bucket)->delete($path);
            Log::info('File deletion status:', ['deleted' => $deleted]);

            if ($deleted) {
                BucketUsage::where('bucket_name', $bucket)
                    ->update(['total_bytes' => DB::raw("GREATEST(total_bytes - {$size}, 0)")]);
            }

            return $deleted;
        } catch (\Exception $e) {
            Log::error("Failed to delete file from bucket {$bucket}: " . $e->getMessage());
            return false;
        }
    }
}

