<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OldSong;

class OldSongController extends Controller
{
    
    public function index()
    {
        $songs = OldSong::all();

        
        foreach ($songs as $song) {
            $song->audio = asset($song->audio);
        }

        return response()->json($songs);
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'artist' => 'required|string|max:255',
            'audio' => 'required|mimes:mp3,wav,ogg|max:10000',
        ]);

        $file = $request->file('audio');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('audio'), $filename);

        $song = OldSong::create([
            'title' => $request->title,
            'artist' => $request->artist,
            'audio' => 'audio/' . $filename,
        ]);

        return response()->json(['message' => 'Song uploaded successfully!', 'data' => $song]);
    }
}


