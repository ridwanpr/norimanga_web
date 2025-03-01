<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\Manga;
use App\Models\MangaChapter;
use App\Models\Genre;

class SitemapController extends Controller
{
    public function index()
    {
        $xml = view('sitemaps.index')->render();
        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    public function manga()
    {
        $mangas = Manga::latest()->get();
        $xml = view('sitemaps.manga', compact('mangas'))->render();
        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    public function chapters()
    {
        $chapters = MangaChapter::with('manga')->latest()->get();
        $xml = view('sitemaps.chapters', compact('chapters'))->render();
        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    public function genres()
    {
        $genres = Genre::latest()->get();
        $xml = view('sitemaps.genres', compact('genres'))->render();
        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
