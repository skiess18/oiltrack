<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Collection;
use App\Models\RoutePlan;
use App\Models\TransportReport;
use App\Services\WarehouseInventoryService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Dashboard
     */
    public function index(WarehouseInventoryService $inventory)
    {
        $todayTransportReport = null;

        if (Auth::user()->isDriver()) {

            $todayTransportReport = TransportReport::with('user')
                ->where('user_id', Auth::id())
                ->whereDate('date', today())
                ->first();

            // Ако няма започнат отчет за днес,
            // показва Dashboard без да пренасочва.
        }

        // Keep the dashboard view's display value scalar; never pass a User
        // model for interpolation, which would render its JSON representation.
        $driverName = optional($todayTransportReport?->user)->name ?? 'Not assigned';
        $hasOpenTransportReport = (bool) ($todayTransportReport?->end_km === null && $todayTransportReport);

        $today = Carbon::today();

        // Общ брой клиенти
        $clientsCount = Client::count();

        // Данни само за текущия ден
        $collectionsCount = Collection::whereDate('collection_date', $today)
            ->count();

        $totalLiters = Collection::whereDate('collection_date', $today)
            ->sum('liters');

        $totalRevenue = Collection::sum('total_price');

        // Запазени за съвместимост с изгледа
        $todayLiters = $totalLiters;
        $todayRevenue = Collection::whereDate('collection_date', $today)
            ->sum('total_price');

        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();
        $currentWarehouseStock = $inventory->currentStock();
        $warehouseIsEmpty = $currentWarehouseStock <= 0;
        $monthCollected = $inventory->collectedBetween($monthStart, $monthEnd);
        $monthRecycled = $inventory->recycledBetween($monthStart, $monthEnd);

        $todayRoutes = RoutePlan::whereDate('route_date', $today)
            ->count();

        $activeRoutes = RoutePlan::whereIn('status', [
            'planned',
            'in_progress',
        ])->count();

        $completedRoutes = RoutePlan::where('status', 'completed')
            ->count();

        $latestCollections = Collection::with('client')
            ->latest('collection_date')
            ->take(8)
            ->get();

        $latestRoutes = RoutePlan::latest('route_date')
            ->take(5)
            ->get();

        $latestClients = Client::latest()
            ->take(5)
            ->get();

        return view(
            'dashboard',
            compact(
                'clientsCount',
                'collectionsCount',
                'totalLiters',
                'totalRevenue',
                'todayLiters',
                'todayRevenue',
                'currentWarehouseStock',
                'warehouseIsEmpty',
                'monthCollected',
                'monthRecycled',
                'todayRoutes',
                'activeRoutes',
                'completedRoutes',
                'latestCollections',
                'latestRoutes',
                'latestClients',
                'driverName',
                'hasOpenTransportReport'
            )
        );
    }
}
