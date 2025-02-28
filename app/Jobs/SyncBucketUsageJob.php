<?php

namespace App\Jobs;

use App\Models\BucketUsage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncBucketUsageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    public function handle()
    {
        $buckets = ['s3', 's4', 's5'];

        foreach ($buckets as $bucket) {
            $redisKey = "bucket_usage:{$bucket}";
            $size = Cache::pull($redisKey);

            if ($size > 0) {
                DB::update("UPDATE bucket_usages SET total_bytes = total_bytes + ? WHERE bucket_name = ?", [$size, $bucket]);
            }
        }
    }
}
