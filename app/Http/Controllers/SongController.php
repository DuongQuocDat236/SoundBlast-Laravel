<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Song;

class SongController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');

        $songs = Song::where('title', 'like', '%' . $query . '%')->get();

        return response()->json($songs);
    }
}
