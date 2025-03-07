<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\Manga;
use App\Models\MangaChapter;
use Carbon\Carbon;

class SitemapController extends Controller
{
    protected $sitemapSize = 1000;

    public function index()
    {
        $mangaCount = Manga::count();
        $chapterCount = MangaChapter::count();

        $mangaParts = ceil($mangaCount / $this->sitemapSize);
        $chapterParts = ceil($chapterCount / $this->sitemapSize);

        $latestMangaUpdate = Manga::latest('updated_at')->first()?->updated_at ?? now();
        $latestChapterUpdate = MangaChapter::latest('updated_at')->first()?->updated_at ?? now();

        return response()
            ->view('sitemaps.index', [
                'mangaParts' => $mangaParts,
                'chapterParts' => $chapterParts,
                'latestMangaUpdate' => $latestMangaUpdate,
                'latestChapterUpdate' => $latestChapterUpdate,
            ], 200)
            ->header('Content-Type', 'application/xml');
    }

    public function manga(Request $request)
    {
        $page = max(1, $request->get('page', 1));

        $mangas = Manga::select('slug', 'updated_at')
            ->latest()
            ->skip(($page - 1) * $this->sitemapSize)
            ->take($this->sitemapSize)
            ->get();

        return response()
            ->view('sitemaps.manga', compact('mangas'), 200)
            ->header('Content-Type', 'application/xml')
            ->header('Cache-Control', 'public, max-age=86400');
    }

    public function chapters(Request $request)
    {
        $page = max(1, $request->get('page', 1));
        $perPage = 1000;

        $chapters = MangaChapter::select('id', 'manga_id', 'chapter_number', 'updated_at')
            ->with([
                'manga' => function ($query) {
                    $query->select('id', 'slug');
                }
            ])
            ->whereHas('manga')
            ->orderBy('id', 'desc')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        if ($chapters->isEmpty()) {
            return response()->json([
                'message' => 'No chapters found for page ' . $page,
                'offset' => ($page - 1) * $perPage,
                'limit' => $perPage,
                'total_chapters' => MangaChapter::count(),
                'total_with_manga' => MangaChapter::whereHas('manga')->count()
            ]);
        }

        return response()
            ->view('sitemaps.chapters', compact('chapters'), 200)
            ->header('Content-Type', 'application/xml');
    }
}
