<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Collection;
use App\Models\RoutePlan;
use App\Models\TransportReport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Dashboard
     */
    public function index()
    {
        // Ако е шофьор и няма транспортен отчет за днес
        if (
            Auth::user()->isDriver() &&
            !TransportReport::where('user_id', Auth::id())
                ->whereDate('date', today())
                ->exists()
        ) {
            return redirect()->route('transport-report.create');
        }

        // Днес
        $today = Carbon::today();

        // Основна статистика
        $clientsCount = Client::count();

        $collectionsCount = Collection::count();

        $totalLiters = Collection::sum('liters');

        $totalRevenue = Collection::sum('total_price');

        // За днес
        $todayLiters = Collection::whereDate(
            'collection_date',
            $today
        )->sum('liters');

        $todayRevenue = Collection::whereDate(
            'collection_date',
            $today
        )->sum('total_price');

        // Маршрути
        $todayRoutes = RoutePlan::whereDate(
            'route_date',
            $today
        )->count();

        $activeRoutes = RoutePlan::whereIn(
            'status',
            [
                'planned',
                'in_progress'
            ]
        )->count();

        $completedRoutes = RoutePlan::where(
            'status',
            'completed'
        )->count();

        // Последни събирания
        $latestCollections = Collection::with('client')
            ->latest('collection_date')
            ->take(8)
            ->get();

        // Последни маршрути
        $latestRoutes = RoutePlan::latest('route_date')
            ->take(5)
            ->get();

        // Последно добавени обекти
        $latestClients = Client::latest()
            ->take(5)
            ->get();

        // Транспортен отчет за днес
        $todayTransportReport = TransportReport::where(
            'user_id',
            Auth::id()
        )
        ->whereDate('date', today())
        ->first();

        return view(
            'dashboard',
            compact(
                'clientsCount',
                'collectionsCount',
                'totalLiters',
                'totalRevenue',
                'todayLiters',
                'todayRevenue',
                'todayRoutes',
                'activeRoutes',
                'completedRoutes',
                'latestCollections',
                'latestRoutes',
                'latestClients',
                'todayTransportReport'
            )
        );
    }
}