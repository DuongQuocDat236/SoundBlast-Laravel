<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrendingNow;

class TrendingNowController extends Controller
{
    public function index()
    {
        return response()->json(TrendingNow::all());
    }
}
?>
