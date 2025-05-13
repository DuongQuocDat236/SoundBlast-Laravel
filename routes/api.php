<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

// 📦 Models
use App\Models\{
    Song, TopChart, NewRelease, LatestAlbum,
    Genre, OldSong, TopArtist, Language,
    TopSearchedSong, Gallery, Review
};

// 🔧 Controllers
use App\Http\Controllers\{
    TrendingNowController, AuthController, GalleryController,
    FeedbackController, SongController, OldSongController
};

//
// 🔐 AUTHENTICATION
//
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/user', fn(Request $request) => $request->user());
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::post('/forgot-password', [AuthController::class, 'sendResetLink']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

//
// 📦 PUBLIC CONTENT APIs
//
Route::get('/trending', [TrendingNowController::class, 'index']);
Route::get('/top-charts', fn() => TopChart::all());
Route::get('/new-releases', fn() => NewRelease::all());
Route::get('/latest-albums', fn() => LatestAlbum::all());
Route::get('/genres', fn() => Genre::all());
Route::get('/old-songs', [OldSongController::class, 'index']);
Route::post('/old-songs', [OldSongController::class, 'store']);
Route::get('/top-artists', fn() => TopArtist::all());
Route::get('/languages', fn() => Language::all());
Route::get('/top-searched-songs', fn() => TopSearchedSong::all());
Route::get('/galleries', [GalleryController::class, 'index']);
Route::get('/reviews', fn() => Review::all());
Route::get('/feedback', [FeedbackController::class, 'index']);
Route::post('/feedback', [FeedbackController::class, 'store']);
Route::get('/songs/search', [SongController::class, 'search']);

//
// 🎧 AUDIO STREAMING (supports byte-range + seeking)
//
Route::get('/stream-audio/{fullpath}', function ($fullpath) {
    $filePath = public_path($fullpath);

    if (!File::exists($filePath)) {
        abort(404, 'File not found');
    }

    $fileSize = File::size($filePath);
    $start = 0;
    $end = $fileSize - 1;
    $length = $fileSize;

    $headers = [
        'Content-Type' => 'audio/mpeg',
        'Accept-Ranges' => 'bytes',
    ];

    if (isset($_SERVER['HTTP_RANGE'])) {
        $range = str_replace('bytes=', '', $_SERVER['HTTP_RANGE']);
        [$rangeStart, $rangeEnd] = explode('-', $range);
        $start = intval($rangeStart);
        $end = is_numeric($rangeEnd) ? intval($rangeEnd) : $fileSize - 1;
        $length = $end - $start + 1;

        $file = fopen($filePath, 'rb');
        fseek($file, $start);

        return Response::stream(function () use ($file, $length) {
            echo fread($file, $length);
            fclose($file);
        }, 206, array_merge($headers, [
            'Content-Range' => "bytes $start-$end/$fileSize",
            'Content-Length' => $length,
        ]));
    }

    return Response::stream(function () use ($filePath) {
        readfile($filePath);
    }, 200, array_merge($headers, [
        'Content-Length' => $fileSize,
    ]));
})->where('fullpath', '.*');
