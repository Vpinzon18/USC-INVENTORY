<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $assets = Asset::orderBy('last_seen_at', 'desc')->get();

        return view('dashboard', compact('assets'));
    }
}