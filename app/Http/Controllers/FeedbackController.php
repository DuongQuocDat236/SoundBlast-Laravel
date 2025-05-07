<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nickname' => 'required|string|max:255',
            'email' => 'required|email',
            'gender' => 'nullable|in:male,female,other',
            'comment' => 'required|string|max:300',
        ]);

        if ($validated['gender'] === '') {
            $validated['gender'] = null;
        }

        Feedback::create($validated);

        return response()->json(['message' => 'Feedback submitted successfully'], 201);
    }

    public function index()
    {
        return Feedback::latest()->get();
    }
}

