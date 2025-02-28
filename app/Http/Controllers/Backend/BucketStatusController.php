<?php

namespace App\Http\Controllers\Backend;

use App\Models\BucketUsage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BucketStatusController extends Controller
{
    public function index()
    {
        $totalBytes = 17 * 1024 * 1024 * 1024; // 17GB
        $bucketUsage = BucketUsage::all()->map(function ($usage) use ($totalBytes) {
            $percent = round(($usage->total_bytes / $totalBytes) * 100, 2);
            return [
                'bucket_name' => $usage->bucket_name,
                'storage_used_bytes' => $usage->total_bytes,
                'storage_used_str' => number_format($usage->total_bytes) . ' bytes',
                'storage_used_gb' => number_format($usage->total_bytes / (1024 * 1024 * 1024), 2) . ' GB',
                'storage_used_mb' => number_format($usage->total_bytes / (1024 * 1024), 2) . ' MB',
                'percent_usage' => $percent . '%',
            ];
        });
        return view('backend.bucket_status.index', compact('bucketUsage'));
    }
}
