<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use Carbon\CarbonPeriod; // <-- 1. Tambahkan import ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getStats(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        $articleQuery = Article::where('status', 'approved')
            ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
                $query->whereBetween('created_at', [$fromDate, $toDate . ' 23:59:59']);
            });

        // Bagian Stat Cards (sudah benar)
        $totalArticles = (clone $articleQuery)->count();
        $totalHits = (clone $articleQuery)->sum('hits');
        $totalViews = (clone $articleQuery)->sum('views');
        $totalUsers = User::count();
        $totalWriters = User::whereHas('roles', fn($query) => $query->where('name', 'Writer'))->count();

        // === 2. PERBAIKAN LOGIKA CHART DIMULAI DI SINI ===
        $chartData = ['labels' => [], 'data' => []];

        if ($fromDate && $toDate) {
            // JIKA ADA FILTER TANGGAL: Tampilkan data harian
            $period = CarbonPeriod::create($fromDate, $toDate);
            $articlesPerDay = (clone $articleQuery)
                ->select(DB::raw("strftime('%Y-%m-%d', created_at) as day"), DB::raw('count(*) as count'))
                ->groupBy('day')->orderBy('day', 'asc')->get()->keyBy('day');

            foreach ($period as $date) {
                $dateKey = $date->format('Y-m-d');
                $chartData['labels'][] = $date->format('M d'); // Label: 'Oct 20'
                $chartData['data'][] = $articlesPerDay->get($dateKey)->count ?? 0;
            }
        } else {
            // JIKA TIDAK ADA FILTER (DEFAULT): Tampilkan 12 bulan terakhir
            $articlesPerMonth = (clone $articleQuery)
                ->select(DB::raw("strftime('%Y-%m', created_at) as month"), DB::raw('count(*) as count'))
                ->where('created_at', '>=', now()->subYear()) // Hanya berlaku di sini
                ->groupBy('month')->orderBy('month', 'asc')->get()->keyBy('month');
            
            for ($i = 11; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $monthKey = $month->format('Y-m');
                $monthName = $month->format('M'); // Label: 'Oct'

                $chartData['labels'][] = $monthName;
                $chartData['data'][] = $articlesPerMonth->get($monthKey)->count ?? 0;
            }
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