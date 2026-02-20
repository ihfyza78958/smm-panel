<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function terms()
    {
        $page = Page::where('slug', 'terms')->where('is_active', true)->first();
        return view('pages.terms', compact('page'));
    }

    public function privacy()
    {
        $page = Page::where('slug', 'privacy')->where('is_active', true)->first();
        return view('pages.privacy', compact('page'));
    }
}
