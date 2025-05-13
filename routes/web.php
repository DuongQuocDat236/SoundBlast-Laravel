<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;


Route::get('/', fn() => view('welcome'));
Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::post('/dev-reset-link', function (Request $request) {
    $user = \App\Models\User::where('email', $request->email)->first();
    if (!$user) return response()->json(['error' => 'User not found'], 404);
    $token = Password::createToken($user);
    $url = 'http://localhost:3000/reset-password?token=' . $token . '&email=' . urlencode($user->email);
    return response()->json(['reset_link' => $url]);
});


Route::get('/stream-audio/{fullpath}', function ($fullpath) {
    $filePath = public_path($fullpath);
    if (!File::exists($filePath)) abort(404, 'File not found');

    $fileSize = File::size($filePath);
    $start = 0;
    $end = $fileSize - 1;
    $headers = ['Content-Type' => 'audio/mpeg', 'Accept-Ranges' => 'bytes'];

    if (isset($_SERVER['HTTP_RANGE'])) {
        [$rangeStart, $rangeEnd] = explode('-', str_replace('bytes=', '', $_SERVER['HTTP_RANGE']));
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
    }, 200, array_merge($headers, ['Content-Length' => $fileSize]));
})->where('fullpath', '.*');

