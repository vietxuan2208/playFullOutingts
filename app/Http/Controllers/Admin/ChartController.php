<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChartController extends Controller
{
    public function orderTimeline()
    {
        $range = request()->range ?? 'ALL';

        switch ($range) {
            case "1W":
                $startDate = Carbon::now()->subDays(7)->startOfDay();
                break;
            case "2W":
                $startDate = Carbon::now()->subDays(14)->startOfDay();
                break;
            case "1M":
                $startDate = Carbon::now()->subMonth()->startOfDay();
                break;
            case "YTD":
                $startDate = Carbon::now()->startOfYear()->startOfDay();
                break;
            case "ALL":
            default:
                $first = DB::table('orders')->min('created_at');
                $startDate = $first
                    ? Carbon::parse($first)->startOfDay()
                    : Carbon::now()->startOfDay();
                break;
        }

        // 🔥 ĐỔI chỗ này: dùng endOfDay thay vì startOfDay
        $maxDate = DB::table('orders')->max('created_at');
        $endDate = $maxDate
            ? Carbon::parse($maxDate)->endOfDay()
            : Carbon::now()->endOfDay();

        $raw = DB::table('orders')
            ->selectRaw('DATE(created_at) AS date, COUNT(*) AS total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->mapWithKeys(function ($item) {
                $key = Carbon::parse($item->date)->toDateString(); // YYYY-MM-DD
                return [$key => (int)$item->total];
            });

        $series = [];
        $d = $startDate->copy();

        while ($d->lte($endDate)) {
            $dateStr = $d->toDateString();
            $series[] = [
                $d->timestamp * 1000,
                $raw->has($dateStr) ? $raw[$dateStr] : 0
            ];
            $d->addDay();
        }

        return response()->json([
            'series' => $series
        ]);
    }

    public function gameByCategory()
    {
        $rows = DB::table('categories')
            ->leftJoin('category_game', 'categories.id', '=', 'category_game.category_id')
            ->where('categories.is_delete', 0)
            ->where('categories.status', 1)
            ->select('categories.name', DB::raw('COUNT(category_game.game_id) AS total'))
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('categories.name')
            ->get();

        return response()->json([
            'labels' => $rows->pluck('name'),
            'series' => $rows->pluck('total'),
        ]);
    }

    public function orderByStatus()
    {
        $rows = DB::table('orders')
            ->selectRaw("
            DATE(created_at) AS order_date,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pending_count,
            SUM(CASE WHEN status = 'shipped' THEN 1 ELSE 0 END) AS shipped_count,
            SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) AS delivered_count,
            SUM(CASE WHEN status = 'canceled' THEN 1 ELSE 0 END) AS canceled_count
        ")
            ->groupBy('order_date')
            ->orderBy('order_date', 'ASC')
            ->get();

        // Format ApexCharts: mỗi series là 1 array values
        return response()->json([
            "categories" => $rows->pluck('order_date'),

            "pending"   => $rows->pluck('pending_count'),
            "shipped"   => $rows->pluck('shipped_count'),
            "delivered" => $rows->pluck('delivered_count'),
            "canceled"  => $rows->pluck('canceled_count'),
        ]);
    }
}
