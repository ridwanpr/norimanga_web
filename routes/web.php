<?php

use App\Http\Controllers\Backend\ChapterController;
use App\Http\Controllers\Backend\UpdateInfoController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\UserIssueController;
use App\Jobs\SyncBucketUsageJob;
use App\Jobs\UpdateBucketUsageJob;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MangaController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\MangaListController;
use App\Http\Controllers\UserAccountController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\AutoMationController;
use App\Http\Controllers\Backend\ManageUserController;
use App\Http\Controllers\Backend\ManageComicController;
use App\Http\Controllers\Backend\BucketStatusController;
use App\Http\Controllers\Backend\MangaChapterController;

Route::get('/', [HomeController::class, 'index'])->name('home')->prerender();
Route::get('daftar-komik', [MangaListController::class, 'gridList'])->name('manga.grid-list')->prerender();
Route::get('daftar-komik/text', [MangaListController::class, 'textList'])->name('manga.text-list')->prerender();

Route::get('komik/{slug}', [MangaController::class, 'show'])->name('manga.show')->prefetch();
Route::get('komik/{slug}/{chapter_slug}', [MangaController::class, 'reader'])->name('manga.reader')->prefetch();
Route::post('chapter-issue', [UserIssueController::class, 'store'])->name('report.chapter');
Route::get('blog', [BlogController::class, 'index'])->name('blog.index');

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'login'])->name('login')->prerender();
    Route::get('register', [AuthController::class, 'register'])->name('register')->prerender();
    Route::post('login', [AuthController::class, 'postLogin'])->name('login.post');
    Route::post('register', [AuthController::class, 'postRegister'])->name('register.post');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

Route::group(['middleware' => ['auth', 'checkRoles:user']], function () {
    Route::get('my-account', [UserAccountController::class, 'myAccount'])->name('my-account')->prerender();
    Route::put('update-profile/{id}', [UserAccountController::class, 'updateProfile'])->name('update-profile');

    Route::get('stats', [StatsController::class, 'index'])->name('stats.index')->prerender();

    Route::post('/bookmark/toggle', [BookmarkController::class, 'toggle']);
    Route::delete('/bookmark/destroy', [BookmarkController::class, 'destroy'])->name('bookmark.destroy');
});

Route::get('bookmark', [BookmarkController::class, 'index'])->name('bookmark.index')->prerender();

Route::group(['middleware' => ['auth', 'checkRoles:admin']], function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard')->prerender();
    Route::prefix('admin/users')->group(function () {
        Route::get('/', [ManageUserController::class, 'index'])->name('admin.users.index');
        Route::post('/ban/{id}', [ManageUserController::class, 'banUser'])->name('admin.users.ban');
        Route::post('/update-password/{id}', [ManageUserController::class, 'updatePassword'])->name('admin.users.update-password');

        Route::get('/manga-chapters/manga-list', [MangaChapterController::class, 'mangaList'])->name('manga-chapters.manga-list');
        Route::resource('manga-chapters', MangaChapterController::class);
    });

    Route::get('chapter/{mangaId}', [ChapterController::class, 'index'])->name('chapter.index');
    Route::get('chapter/{mangaId}/{chapterId}/edit', [ChapterController::class, 'edit'])->name('chapter.edit');
    Route::put('chapter/{mangaId}/{chapterId}/update', [ChapterController::class, 'update'])->name('chapter.update');
    Route::get('chapter/{mangaId}/create', [ChapterController::class, 'create'])->name('chapter.create');
    Route::post('chapter/{mangaId}', [ChapterController::class, 'store'])->name('chapter.store');

    Route::get('automation', [AutoMationController::class, 'index'])->name('automation.index');
    Route::get('/manga/search', [AutoMationController::class, 'search'])->name('automation.chapter.search');
    Route::post('automation/fetch-manga', [AutoMationController::class, 'fetchManga'])->name('automation.fetch.manga');
    Route::post('automation/fetch-chapter', [AutoMationController::class, 'fetchChapter'])->name('automation.fetch.chapter');
    Route::post('automation/fetch-chapter-image', [AutoMationController::class, 'fetchChapterImage'])->name('automation.fetch.chapter-image');

    Route::get('manga-update', [UpdateInfoController::class, 'index'])->name('update.index');

    Route::get('usser-issue', [\App\Http\Controllers\Backend\UserIssueController::class, 'index'])->name('backend.user-issue.index');
    Route::post('user-issue/solve', [\App\Http\Controllers\Backend\UserIssueController::class, 'solve'])->name('backend.user-issue.solve');

    Route::get('refresh-cache', function () {
        Artisan::call('cache:clear');
        return back()->with('success', 'Cache cleared successfully.');
    })->name('refresh-cache');

    Route::get('storage-status', [BucketStatusController::class, 'index'])->name('storage-status');
    Route::resource('manage-comic', ManageComicController::class);
});

Route::get('/xvqxv', function () {
    dispatch(new UpdateBucketUsageJob());
    return response()->json(['message' => 'Bucket usage job dispatched successfully.']);
});

Route::get('/kjhku', function () {
    phpinfo();
});

Route::get('bhilmnuqwecvrl', function () {
    dispatch(new SyncBucketUsageJob());
    return response()->json(['message' => 'Bucket usage job dispatched successfully.']);
});

Route::get('mn92xc4go67', 'App\Http\Controllers\DatabaseBackupController@backup');

Route::get('/sitemap.xml', [SitemapController::class, 'index']);
Route::get('/sitemap-manga.xml', [SitemapController::class, 'manga']);
Route::get('/sitemap-chapters.xml', [SitemapController::class, 'chapters']);

Route::get('{path}', function ($path) {
    abort(403);
})->where('path', '^\.|wp-config\.php|config\.php|phpinfo\.php|xmlrpc\.php|wp-admin.*|wp-.*\.php|cgi-bin.*');

Route::get('a', fn() => 'Hello World');
