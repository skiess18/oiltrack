<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Collection;
use App\Models\RoutePlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CollectionController extends Controller
{
    /**
     * Покажи всички събирания за даден обект
     */
    public function index(Client $client)
    {
        $collections = Collection::where('client_id', $client->id)
            ->orderByDesc('collection_date')
            ->get();

        return view('collections.index', compact(
            'client',
            'collections'
        ));
    }

    /**
     * Форма за ново събиране
     */
    public function create(Client $client)
    {
        return view('collections.create', compact('client'));
    }

    /**
     * Запис на ново събиране
     */
    public function store(Request $request, Client $client)
    {
        $validated = $request->validate([
            'collection_date' => 'required|date',
            'liters' => 'required|numeric|min:0',
            'price_per_liter' => 'required|numeric|min:0',
            'notes' => 'nullable|string',

            'route_id' => 'nullable|exists:route_plans,id',

            'signature' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Запис на подписа като PNG
        |--------------------------------------------------------------------------
        */

        $signaturePath = null;

        if (!empty($validated['signature'])) {

            $image = str_replace(
                'data:image/png;base64,',
                '',
                $validated['signature']
            );

            $image = str_replace(' ', '+', $image);

            $fileName = 'signature_' . time() . '.png';

            Storage::disk('public')->put(
                'signatures/' . $fileName,
                base64_decode($image)
            );

            $signaturePath = 'signatures/' . $fileName;
        }

        /*
        |--------------------------------------------------------------------------
        | Запис на събирането
        |--------------------------------------------------------------------------
        */

        $collection = Collection::create([

            'client_id' => $client->id,

            'collection_date' => $validated['collection_date'],

            'liters' => $validated['liters'],

            'price_per_liter' => $validated['price_per_liter'],

            'total_price' => $validated['liters'] * $validated['price_per_liter'],

            'notes' => $validated['notes'] ?? null,

            'signature' => $signaturePath,

            'latitude' => $validated['latitude'] ?? null,

            'longitude' => $validated['longitude'] ?? null,

        ]);

        /*
        |--------------------------------------------------------------------------
        | Ако събирането е част от маршрут
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['route_id'])) {

            $route = RoutePlan::find($validated['route_id']);

            if ($route) {

                $route->clients()->updateExistingPivot(
                    $client->id,
                    [
                        'visited' => true,
                    ]
                );

                $nextClient = $route->clients()
                    ->wherePivot('visited', false)
                    ->orderByPivot('position')
                    ->first();

                if ($nextClient) {

                    return redirect()
                        ->route('routes.drive', $route)
                        ->with(
                            'success',
                            'Събирането беше записано успешно.'
                        );
                }

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
        }

        /*
        |--------------------------------------------------------------------------
        | Ако не е маршрут
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('collections.index', $client)
            ->with(
                'success',
                'Събирането беше добавено успешно.'
            );
    }

    /**
     * Покажи конкретно събиране
     */
    public function show(Collection $collection)
    {
        return view('collections.show', compact('collection'));
    }

    /**
     * Форма за редакция
     */
    public function edit(Collection $collection)
    {
        return view('collections.edit', compact('collection'));
    }

    /**
     * Обновяване
     */
    public function update(Request $request, Collection $collection)
    {
        $validated = $request->validate([
            'collection_date' => 'required|date',
            'liters' => 'required|numeric|min:0',
            'price_per_liter' => 'required|numeric|min:0',
            'notes' => 'nullable|string',

            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $collection->update([

            'collection_date' => $validated['collection_date'],

            'liters' => $validated['liters'],

            'price_per_liter' => $validated['price_per_liter'],

            'total_price' => $validated['liters'] * $validated['price_per_liter'],

            'notes' => $validated['notes'] ?? null,

            'latitude' => $validated['latitude'] ?? null,

            'longitude' => $validated['longitude'] ?? null,

        ]);

        return back()->with(
            'success',
            'Събирането беше обновено успешно.'
        );
    }

    /**
     * Изтриване
     */
    public function destroy(Collection $collection)
    {
        if ($collection->signature) {

            Storage::disk('public')->delete(
                $collection->signature
            );

        }

        $client = $collection->client;

        $collection->delete();

        return redirect()
            ->route('collections.index', $client)
            ->with(
                'success',
                'Събирането беше изтрито.'
            );
    }
}