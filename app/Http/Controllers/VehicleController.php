<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VehicleController extends Controller
{
    /**
     * Списък с автомобили
     */
    public function index()
    {
        if (auth()->user()->isDriver()) {
            $vehicles = Vehicle::where('driver_id', auth()->id())
                ->orderBy('registration')
                ->paginate(12);
        } else {
            $vehicles = Vehicle::orderBy('registration')
                ->paginate(12);
        }

        return view('vehicles.index', compact('vehicles'));
    }

    /**
     * Форма за нов автомобил
     */
    public function create()
    {
        $drivers = User::where('role', 'driver')
            ->orderBy('name')
            ->get();

        return view('vehicles.create', compact('drivers'));
    }

    /**
     * Запис на автомобил
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'nullable|integer|min:1950|max:2100',

            'registration' => 'required|string|max:30|unique:vehicles',

            'vin' => 'nullable|string|max:50',

            'color' => 'nullable|string|max:100',

            'driver' => 'nullable|string|max:255',

            'driver_id' => 'nullable|exists:users,id',

            'fuel_consumption' => 'nullable|numeric|min:0',

            'current_km' => 'nullable|integer|min:0',

            'last_service' => 'nullable|date',

            'next_service_km' => 'nullable|integer|min:0',

            'inspection_date' => 'nullable|date',

            'insurance_date' => 'nullable|date',

            'status' => 'required|in:active,service,inactive',

            'notes' => 'nullable|string',

            'photo' => 'nullable|image|max:4096'

        ]);

        if ($request->filled('driver_id')) {
            $validated['driver'] = User::find($request->driver_id)?->name;
        }

        if ($request->hasFile('photo')) {

            $validated['photo'] = $request
                ->file('photo')
                ->store('vehicles', 'public');
        }

        Vehicle::create($validated);

        return redirect()
            ->route('vehicles.index')
            ->with(
                'success',
                'Автомобилът беше добавен успешно.'
            );
    }
        /**
     * Детайли
     */
    public function show(Vehicle $vehicle)
    {
        if (auth()->user()->isDriver() && $vehicle->driver_id != auth()->id()) {
            abort(403);
        }

        return view(
            'vehicles.show',
            compact('vehicle')
        );
    }

    /**
     * Форма за редакция
     */
    public function edit(Vehicle $vehicle)
    {
        if (auth()->user()->isDriver()) {
            abort(403);
        }

        $drivers = User::where('role', 'driver')
            ->orderBy('name')
            ->get();

        return view(
            'vehicles.edit',
            compact(
                'vehicle',
                'drivers'
            )
        );
    }

    /**
     * Обновяване
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        if (auth()->user()->isDriver()) {
            abort(403);
        }

        $validated = $request->validate([

            'brand' => 'required|string|max:255',

            'model' => 'required|string|max:255',

            'year' => 'nullable|integer|min:1950|max:2100',

            'registration' => 'required|string|max:30|unique:vehicles,registration,' . $vehicle->id,

            'vin' => 'nullable|string|max:50',

            'color' => 'nullable|string|max:100',

            'driver' => 'nullable|string|max:255',

            'driver_id' => 'nullable|exists:users,id',

            'fuel_consumption' => 'nullable|numeric|min:0',

            'current_km' => 'nullable|integer|min:0',

            'last_service' => 'nullable|date',

            'next_service_km' => 'nullable|integer|min:0',

            'inspection_date' => 'nullable|date',

            'insurance_date' => 'nullable|date',

            'status' => 'required|in:active,service,inactive',

            'notes' => 'nullable|string',

            'photo' => 'nullable|image|max:4096'

        ]);

        if ($request->filled('driver_id')) {
            $validated['driver'] = User::find($request->driver_id)?->name;
        }

        if ($request->hasFile('photo')) {

            if ($vehicle->photo) {

                Storage::disk('public')->delete($vehicle->photo);

            }

            $validated['photo'] = $request
                ->file('photo')
                ->store('vehicles', 'public');

        }

        $vehicle->update($validated);

        return redirect()
            ->route('vehicles.show', $vehicle)
            ->with(
                'success',
                'Автомобилът беше обновен успешно.'
            );
    }

    /**
     * Изтриване
     */
    public function destroy(Vehicle $vehicle)
    {
        if (auth()->user()->isDriver()) {
            abort(403);
        }

        if ($vehicle->photo) {

            Storage::disk('public')->delete($vehicle->photo);

        }

        $vehicle->delete();

        return redirect()
            ->route('vehicles.index')
            ->with(
                'success',
                'Автомобилът беше изтрит успешно.'
            );
    }
}