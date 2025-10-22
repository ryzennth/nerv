<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Article;
use App\Models\Photo;
use App\Models\User;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon; // Tambahkan Carbon

class DashboardController extends Controller
{
    public function getStats(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        // --- STAT CARDS ---
        $articleQuery = Article::where('status', 'approved')
            ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
                // Tambahkan Carbon::parse untuk memastikan format tanggal benar
                $query->whereBetween('created_at', [Carbon::parse($fromDate)->startOfDay(), Carbon::parse($toDate)->endOfDay()]);
            });

        $totalArticles = (clone $articleQuery)->count();
        $totalHits = (clone $articleQuery)->sum('hits');
        $totalViews = (clone $articleQuery)->sum('views');
        $totalUsers = User::count();
        $totalWriters = User::whereHas('roles', fn($query) => $query->where('name', 'writer'))->count(); // Gunakan 'Writer'

        // --- CHARTS ---
        $chartDataArticles = $this->generateChartData($articleQuery, 'created_at', 'count');
        $chartDataAlbumViews = $this->generateChartData(Album::query(), 'created_at', 'sum', 'views', $fromDate, $toDate);
        $chartDataPhotoHits = $this->generateChartData(Photo::query(), 'created_at', 'sum', 'hits', $fromDate, $toDate);

        // --- RESPONSE ---
        return response()->json([
            'stats' => [
                'total_users' => $totalUsers,
                'total_articles' => $totalArticles,
                'total_writers' => $totalWriters,
                'total_hits' => $totalHits,
                'total_views' => $totalViews,
            ],
            'charts' => [
                'articles' => $chartDataArticles,
                'albumViews' => $chartDataAlbumViews,
                'photoHits' => $chartDataPhotoHits,
            ]
        ]);
    }

    /**
     * Helper function to generate chart data (daily if filtered, monthly otherwise).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $dateColumn
     * @param string $aggregateFunction ('count', 'sum')
     * @param string|null $aggregateColumn (null for count, column name for sum)
     * @param string|null $fromDate
     * @param string|null $toDate
     * @return array
     */
    private function generateChartData($query, string $dateColumn, string $aggregateFunction, string $aggregateColumn = null, ?string $fromDate = null, ?string $toDate = null): array
    {
        $chartData = ['labels' => [], 'data' => []];

        // Terapkan filter tanggal ke query jika ada
        $filteredQuery = (clone $query)->when($fromDate && $toDate, function ($q) use ($dateColumn, $fromDate, $toDate) {
            $q->whereBetween($dateColumn, [Carbon::parse($fromDate)->startOfDay(), Carbon::parse($toDate)->endOfDay()]);
        });

        if ($fromDate && $toDate) {
            // JIKA ADA FILTER TANGGAL: Tampilkan data harian
            $period = CarbonPeriod::create($fromDate, $toDate);
            $dbFormat = '%Y-%m-%d'; // Format tanggal database
            $groupBy = 'day';
            $labelFormat = 'M d'; // Label: 'Oct 20'

            $dbQuery = (clone $filteredQuery)
                ->select(DB::raw("strftime('$dbFormat', $dateColumn) as $groupBy"), DB::raw("$aggregateFunction(".($aggregateColumn ?? '*').") as value"))
                ->groupBy($groupBy)->orderBy($groupBy, 'asc')->get()->keyBy($groupBy);

            foreach ($period as $date) {
                $dateKey = $date->format('Y-m-d');
                $chartData['labels'][] = $date->format($labelFormat);
                $chartData['data'][] = $dbQuery->get($dateKey)->value ?? 0;
            }

        } else {
            // JIKA TIDAK ADA FILTER (DEFAULT): Tampilkan 12 bulan terakhir
            $dbFormat = '%Y-%m'; // Format bulan database
            $groupBy = 'month';
            $labelFormat = 'M'; // Label: 'Oct'

            $dbQuery = (clone $filteredQuery) // Gunakan query awal (tanpa filter tanggal spesifik)
                ->select(DB::raw("strftime('$dbFormat', $dateColumn) as $groupBy"), DB::raw("$aggregateFunction(".($aggregateColumn ?? '*').") as value"))
                ->where($dateColumn, '>=', now()->subYear()->startOfMonth()) // Ambil data dari awal bulan setahun lalu
                ->groupBy($groupBy)->orderBy($groupBy, 'asc')->get()->keyBy($groupBy);

            for ($i = 11; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $monthKey = $month->format('Y-m');
                $chartData['labels'][] = $month->format($labelFormat);
                $chartData['data'][] = $dbQuery->get($monthKey)->value ?? 0;
            }
        }

        return $chartData;
    }
}