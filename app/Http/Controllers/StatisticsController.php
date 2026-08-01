<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'week');

        $query = Collection::query();

        switch ($period) {
            case 'week':
                $query->whereBetween('collection_date', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek(),
                ]);
                break;

            case 'month':
                $query->whereYear('collection_date', now()->year)
                      ->whereMonth('collection_date', now()->month);
                break;

            case 'year':
                $query->whereYear('collection_date', now()->year);
                break;

            case 'all':
            default:
                break;
        }

        return view('statistics.index', [
            'period' => $period,
            'totalLiters' => (clone $query)->sum('liters'),
            'totalPaid' => (clone $query)->sum('total_price'),
            'collectionsCount' => (clone $query)->count(),
        ]);
    }
}