<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Song;

class SongController extends Controller
{
    public function search(Request $request)
{
    $query = $request->input('query');

    $songs = Song::where('title', 'like', '%' . $query . '%')
        ->orWhere('artist', 'like', '%' . $query . '%')
        ->orWhere('album', 'like', '%' . $query . '%')
        ->orWhere('genre', 'like', '%' . $query . '%')
        ->orWhere('language', 'like', '%' . $query . '%')
        ->get();

    return response()->json($songs);
}
    public function show($id)
{
    $song = Song::find($id);

    if (!$song) {
        return response()->json(['message' => 'Song not found'], 404);
    }

    return response()->json($song);
}
}
