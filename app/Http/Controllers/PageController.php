<?php

namespace App\Http\Controllers;

use App\Models\Recognition;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PageController extends Controller
{
    public function inference(): View
    {
        $recentRecognitions = Auth::user()
            ->recognitions()
            ->latest()
            ->take(5)
            ->get();

        return view('user.pages.inference', compact('recentRecognitions'));
    }
}
