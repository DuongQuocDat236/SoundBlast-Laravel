<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrendingNowController;
use App\Http\Controllers\AuthController;
use App\Models\TopChart;
use App\Models\NewRelease;
use App\Models\LatestAlbum;
use App\Models\Genre;
use App\Models\OldSong;
use App\Models\TopArtist;
use App\Models\Language;
use App\Models\TopSearchedSong;
use App\Models\Gallery;
use App\Http\Controllers\GalleryController;
use App\Models\Review;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\SongController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/trending', [TrendingNowController::class, 'index']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::get('/top-charts', function () {
    return TopChart::all();
});
Route::get('/new-releases', function () {
    return NewRelease::all();
});
Route::get('/latest-albums', function () {
    return LatestAlbum::all();
});
Route::get('/genres', function () {
    return Genre::all();
});
Route::get('/old-songs', function () {
    return OldSong::all();
});
Route::get('/top-artists', function () {
    return TopArtist::all();
});
Route::get('/languages', function () {
    return Language::all();
});
Route::get('/top-searched-songs', function () {
    return TopSearchedSong::all();
});
Route::get('/galleries', function () {
    return Gallery::all();
});
Route::get('/galleries', [GalleryController::class, 'index']);
Route::get('/reviews', function () {
    return Review::all();
});
Route::get('/feedback', [FeedbackController::class, 'index']);
Route::post('/feedback', [FeedbackController::class, 'store']);
Route::post('/forgot-password', [AuthController::class, 'sendResetLink']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::get('/songs/search', [SongController::class, 'search']);