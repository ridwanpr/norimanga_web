<?php

use App\Jobs\SyncBucketUsageJob;
use App\Jobs\UpdateBucketUsageJob;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImageController;
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

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('daftar-komik', [MangaListController::class, 'gridList'])->name('manga.grid-list');
Route::get('daftar-komik/text', [MangaListController::class, 'textList'])->name('manga.text-list');

Route::get('komik/{slug}', [MangaController::class, 'show'])->name('manga.show');
Route::get('komik/{slug}/{chapter_slug}', [MangaController::class, 'reader'])->name('manga.reader');

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::get('register', [AuthController::class, 'register'])->name('register');
    Route::post('login', [AuthController::class, 'postLogin'])->name('login.post');
    Route::post('register', [AuthController::class, 'postRegister'])->name('register.post');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

Route::group(['middleware' => ['auth', 'checkRoles:user']], function () {
    Route::get('my-account', [UserAccountController::class, 'myAccount'])->name('my-account');
    Route::put('update-profile/{id}', [UserAccountController::class, 'updateProfile'])->name('update-profile');

    Route::get('stats', [StatsController::class, 'index'])->name('stats.index');

    Route::post('/bookmark/toggle', [BookmarkController::class, 'toggle']);
    Route::delete('/bookmark/destroy', [BookmarkController::class, 'destroy'])->name('bookmark.destroy');
});

Route::get('bookmark', [BookmarkController::class, 'index'])->name('bookmark.index');

Route::group(['middleware' => ['auth', 'checkRoles:admin']], function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::prefix('admin/users')->group(function () {
        Route::get('/', [ManageUserController::class, 'index'])->name('admin.users.index');
        Route::post('/ban/{id}', [ManageUserController::class, 'banUser'])->name('admin.users.ban');
        Route::post('/update-password/{id}', [ManageUserController::class, 'updatePassword'])->name('admin.users.update-password');

        Route::get('/manga-chapters/manga-list', [MangaChapterController::class, 'mangaList'])->name('manga-chapters.manga-list');
        Route::resource('manga-chapters', MangaChapterController::class);
    });

    Route::get('automation', [AutoMationController::class, 'index'])->name('automation.index');
    Route::get('/manga/search', [AutoMationController::class, 'search'])->name('automation.chapter.search');
    Route::post('automation/fetch-manga', [AutoMationController::class, 'fetchManga'])->name('automation.fetch.manga');
    Route::post('automation/fetch-chapter', [AutoMationController::class, 'fetchChapter'])->name('automation.fetch.chapter');

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
Route::get('/sitemap-genres.xml', [SitemapController::class, 'genres']);

Route::get('/wp-config.php', function () {
    return response("<h1>Nice try, script kiddie. Too bad your skills are as weak as your mom’s parenting. Go cry to her about how you failed again—maybe she’ll finally teach you something useful, like how to not suck at life.</h1><br><h1>Fuck off, loser. You’re not even worth the bandwidth.</h1>", 200)
        ->header('Content-Type', 'text/html');
});
