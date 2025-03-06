<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use App\Models\Manga;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UpdateInfoController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        $manga = Manga::join('manga_detail', 'manga_detail.id', 'manga.id')
            ->select(
                'manga.title',
                'manga_detail.updated_at',
                'manga.id'
            )
            ->paginate(20);

        foreach ($manga as $m) {
            $m->readable_date = Carbon::parse($m->updated_at)->format('l, d F Y');
            $m->time_difference = Carbon::parse($m->updated_at)->diffForHumans();
        }

        $mangaToUpdate = [
            'oneWeek' => Manga::join('manga_detail', 'manga_detail.id', 'manga.id')
                ->select('manga.title', 'manga_detail.updated_at', 'manga.id')
                ->where('manga_detail.updated_at', '<=', $now->copy()->subWeek())
                ->where('manga_detail.updated_at', '>', $now->copy()->subWeeks(2))
                ->orderBy('manga_detail.updated_at', 'asc')
                ->limit(5)
                ->get(),

            'twoWeeks' => Manga::join('manga_detail', 'manga_detail.id', 'manga.id')
                ->select('manga.title', 'manga_detail.updated_at', 'manga.id')
                ->where('manga_detail.updated_at', '<=', $now->copy()->subWeeks(2))
                ->where('manga_detail.updated_at', '>', $now->copy()->subWeeks(3))
                ->orderBy('manga_detail.updated_at', 'asc')
                ->limit(10)
                ->get(),

            'threeWeeks' => Manga::join('manga_detail', 'manga_detail.id', 'manga.id')
                ->select('manga.title', 'manga_detail.updated_at', 'manga.id')
                ->where('manga_detail.updated_at', '<=', $now->copy()->subWeeks(3))
                ->where('manga_detail.updated_at', '>', $now->copy()->subMonth())
                ->orderBy('manga_detail.updated_at', 'asc')
                ->limit(10)
                ->get(),

            'oneMonth' => Manga::join('manga_detail', 'manga_detail.id', 'manga.id')
                ->select('manga.title', 'manga_detail.updated_at', 'manga.id')
                ->where('manga_detail.updated_at', '<=', $now->copy()->subMonth())
                ->orderBy('manga_detail.updated_at', 'asc')
                ->limit(10)
                ->get()
        ];

        return view('backend.update.index', compact('manga', 'mangaToUpdate'));
    }
}
