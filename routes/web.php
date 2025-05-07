<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});
Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/dev-reset-link', function (Request $request) {
    $user = \App\Models\User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }

    $token = Password::createToken($user);

    $url = 'http://localhost:3000/reset-password?token=' . $token . '&email=' . urlencode($user->email);

    return response()->json(['reset_link' => $url]);
});
