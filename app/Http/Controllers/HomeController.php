<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Hanya artikel yang approved
        $articles = Article::with('user')
            ->where('status', 'approved')
            ->latest()
            ->take(6)
            ->get();

        $popular = Article::with('user')
            ->where('status', 'approved')
            ->orderByDesc('hits')
            ->take(5)
            ->get();

        return Inertia::render('Home', [
            'articles' => $articles,
            'popular'  => $popular,
        ]);
    }
}
