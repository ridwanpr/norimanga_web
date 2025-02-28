<?php

namespace App\Http\Controllers;

use App\Models\UserActivity;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function index()
    {
        $userActivities = UserActivity::with(['manga.detail', 'chapter'])
            ->where('user_id', auth()->user()->id)
            ->paginate(10)
            ->map(function ($userActivity) {
                $userActivity->manga->detail->cover = str_replace('.s3.tebi.io', '', $userActivity->manga->detail->cover);
                return $userActivity;
            });

        return view('stats.index', compact('userActivities'));
    }
}
