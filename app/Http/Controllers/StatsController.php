<?php

namespace App\Http\Controllers;

use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StatsController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $page = request()->get('page', 1);

        $totalManga = Cache::remember("total_manga_{$userId}", now()->addMinutes(10), function () use ($userId) {
            return UserActivity::where('user_id', $userId)->distinct('manga_id')->count('manga_id');
        });

        $totalChapters = Cache::remember("total_chapters_{$userId}", now()->addMinutes(10), function () use ($userId) {
            return UserActivity::where('user_id', $userId)->count();
        });

        $userActivities = Cache::remember("user_activities_{$userId}_page_{$page}", now()->addMinutes(10), function () use ($userId) {
            $query = UserActivity::with(['manga.detail', 'chapter'])
                ->where('user_id', $userId)
                ->orderBy('created_at', 'desc');

            $activities = $query->paginate(15);

            $activities->getCollection()->transform(function ($userActivity) {
                $userActivity->manga->detail->cover = str_replace('.s3.tebi.io', '', $userActivity->manga->detail->cover);
                return $userActivity;
            });

            return $activities;
        });

        return view('stats.index', compact('userActivities', 'totalManga', 'totalChapters'));
    }
}
