<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getStats()
    {
        // 1. Data untuk "Stat Cards"
        $totalUsers = User::count();
        $totalArticles = Article::where('status', 'approved')->count();
        // Asumsi role penulis adalah 'Writer'
        $totalWriters = User::whereHas('roles', fn($query) => $query->where('name', 'writer'))->count();

        $totalHits = Article::where('status', 'approved')->sum('hits');
        $totalViews = Article::where('status', 'approved')->sum('views');

        // 2. Data untuk Chart (Artikel per Bulan dalam 12 bulan terakhir)
        $articlesPerMonth = Article::select(
                DB::raw("strftime('%Y-%m', created_at) as month"),
                DB::raw('count(*) as count')
            )
            ->where('created_at', '>=', now()->subYear())
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get()
            ->keyBy('month'); // Hasilnya: {'2025-01': {count: 5}, ...}

        // Siapkan array untuk 12 bulan dengan nilai default 0
        $chartData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthKey = $month->format('Y-m');
            $monthName = $month->format('M'); // e.g., 'Oct'

            $chartData['labels'][] = $monthName;
            $chartData['data'][] = $articlesPerMonth->get($monthKey)->count ?? 0;
        }

        return response()->json([
            'stats' => [
                'total_users' => $totalUsers,
                'total_articles' => $totalArticles,
                'total_writers' => $totalWriters,
                'total_hits' => $totalHits,
                'total_views' => $totalViews,
            ],
            'chart' => $chartData,
        ]);
    }
}