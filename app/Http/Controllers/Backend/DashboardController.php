<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Manga;
use App\Models\MangaChapter;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUser = User::where('role_id', 2)->count();
        $totalComic = Manga::whereHas('detail')->count();
        $totalChapter = MangaChapter::where('image', '!=', '[]')->count();
        $totalProject = Manga::where('is_project', true)->count();

        $latestUsers = User::where('role_id', 2)->latest()->take(10)->get();
        $latestManga = Manga::whereHas('detail')->latest()->take(10)->get();
        $latestChapters = MangaChapter::where('image', '!=', '[]')->latest('updated_at')->take(10)->get();

        return view('backend.dashboard', compact('totalUser', 'totalComic', 'totalChapter', 'latestUsers', 'latestManga', 'latestChapters', 'totalProject'));
    }
}
