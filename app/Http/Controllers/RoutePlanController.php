<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\RoutePlan;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RoutePlanController extends Controller
{
    public function index()
    {
        $routes = RoutePlan::with([
            'clients',
            'driver',
            'vehicle'
        ])
        ->latest('route_date')
        ->paginate(15);

        return view('routes.index', compact('routes'));
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get();

        $drivers = User::orderBy('name')->get();

        $vehicles = Vehicle::orderBy('registration')->get();

        return view('routes.create', compact(
            'clients',
            'drivers',
            'vehicles'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([

            'route_date' => 'required|date',

            'driver_id' => 'required|exists:users,id',

            'vehicle_id' => 'required|exists:vehicles,id',

            'notes' => 'nullable|string',

            'status' => 'required|in:planned,in_progress,completed,cancelled',

            'clients' => 'nullable|array',

            'clients.*' => 'exists:clients,id',

        ]);

        $route = RoutePlan::create([

            'route_date' => $validated['route_date'],

            'driver_id' => $validated['driver_id'],

            'vehicle_id' => $validated['vehicle_id'],

            'notes' => $validated['notes'] ?? null,

            'status' => $validated['status'],

        ]);

        if (!empty($validated['clients'])) {

            $sync = [];

            foreach ($validated['clients'] as $i => $clientId) {

                $sync[$clientId] = [

                    'position' => $i + 1,

                    'visited' => false,

                ];

            }

            $route->clients()->sync($sync);

        }

        return redirect()
            ->route('routes.show', $route)
            ->with(
                'success',
                'Маршрутът беше създаден успешно.'
            );
    }
        public function show(RoutePlan $route)
    {
        $route->load([
            'clients' => fn($q) => $q->orderBy('position'),
            'driver',
            'vehicle'
        ]);

        return view('routes.show', compact('route'));
    }

    public function edit(RoutePlan $route)
    {
        $clients = Client::orderBy('name')->get();

        $drivers = User::orderBy('name')->get();

        $vehicles = Vehicle::orderBy('registration')->get();

        $route->load('clients');

        return view(
            'routes.edit',
            compact(
                'route',
                'clients',
                'drivers',
                'vehicles'
            )
        );
    }

    public function update(Request $request, RoutePlan $route)
    {
        $validated = $request->validate([

            'route_date' => 'required|date',

            'driver_id' => 'required|exists:users,id',

            'vehicle_id' => 'required|exists:vehicles,id',

            'notes' => 'nullable|string',

            'status' => 'required|in:planned,in_progress,completed,cancelled',

            'clients' => 'nullable|array',

            'clients.*' => 'exists:clients,id',

        ]);

        $route->update([

            'route_date' => $validated['route_date'],

            'driver_id' => $validated['driver_id'],

            'vehicle_id' => $validated['vehicle_id'],

            'notes' => $validated['notes'] ?? null,

            'status' => $validated['status'],

        ]);

        $sync = [];

        foreach (($validated['clients'] ?? []) as $i => $clientId) {

            $sync[$clientId] = [

                'position' => $i + 1,

                'visited' => false,

            ];

        }

        $route->clients()->sync($sync);

        return redirect()
            ->route('routes.show', $route)
            ->with(
                'success',
                'Маршрутът беше обновен успешно.'
            );
    }

    public function destroy(RoutePlan $route)
    {
        $route->clients()->detach();

        $route->delete();

        return redirect()
            ->route('routes.index')
            ->with(
                'success',
                'Маршрутът беше изтрит успешно.'
            );
    }
        public function drive(RoutePlan $route)
    {
        $route->load([
            'driver',
            'vehicle',
            'clients' => fn($q) => $q->orderBy('position')
        ]);

        $client = $route->clients()
            ->wherePivot('visited', false)
            ->orderBy('position')
            ->first();

        if (!$client) {

            $route->update([
                'status' => 'completed',
            ]);

            return redirect()
                ->route('routes.show', $route)
                ->with(
                    'success',
                    '🎉 Маршрутът беше завършен успешно.'
                );
        }

        if ($route->status === 'planned') {

            $route->update([
                'status' => 'in_progress',
            ]);

        }

        return view(
            'routes.drive',
            compact(
                'route',
                'client'
            )
        );
    }

    public function visit(RoutePlan $route, Client $client)
    {
        $route->clients()->updateExistingPivot(
            $client->id,
            [
                'visited' => true,
            ]
        );

        return $this->finishOrContinue($route);
    }

    public function arrive(Request $request, RoutePlan $route, Client $client)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $route->clients()->updateExistingPivot(
            $client->id,
            [
                'visited' => true,
                'arrived_at' => Carbon::now(),
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]
        );

        $next = $route->clients()
            ->wherePivot('visited', false)
            ->orderBy('position')
            ->first();

        if (!$next) {

            $route->update([
                'status' => 'completed',
            ]);

            return response()->json([
                'completed' => true,
                'redirect' => route('routes.show', $route),
            ]);
        }

        return response()->json([
            'completed' => false,
            'redirect' => route('routes.drive', $route),
        ]);
    }
        /**
     * Оптимизация на маршрута
     */
    public function optimize(RoutePlan $route)
    {
        return redirect()
            ->route('routes.edit', $route)
            ->with(
                'success',
                '🤖 Автоматичната оптимизация ще бъде добавена скоро.'
            );
    }

    /**
     * Проверява дали маршрутът е приключил
     */
    protected function finishOrContinue(RoutePlan $route)
    {
        $next = $route->clients()
            ->wherePivot('visited', false)
            ->orderBy('position')
            ->first();

        if (!$next) {

            $route->update([
                'status' => 'completed',
            ]);

            return redirect()
                ->route('routes.show', $route)
                ->with(
                    'success',
                    '🎉 Маршрутът беше завършен успешно.'
                );
        }

        return redirect()
            ->route('routes.drive', $route)
            ->with(
                'success',
                '✅ Обектът беше маркиран като посетен.'
            );
    }
}