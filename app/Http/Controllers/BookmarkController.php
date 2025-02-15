<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Bookmark;

class BookmarkController extends Controller
{
    public function index()
    {
        $bookmarks = Auth::check()
            ? Bookmark::where('user_id', Auth::id())->with('manga')->get()
            : collect();

        return view('bookmark.index', compact('bookmarks'));
    }
}
