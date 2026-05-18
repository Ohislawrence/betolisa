<?php

namespace App\Http\Controllers;

use App\Models\Tip;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $tips = Tip::select('id', 'updated_at')
            ->where('type', 'free')
            ->orderByDesc('updated_at')
            ->get();

        $content = view('sitemap', compact('tips'))->render();

        return response($content, 200)
            ->header('Content-Type', 'application/xml');
    }
}
