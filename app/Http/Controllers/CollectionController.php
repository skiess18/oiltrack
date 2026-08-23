<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Collection;
use App\Models\RoutePlan;
use App\Models\TransportReport;
use App\Services\WarehouseInventoryService;
use App\Services\ProtocolService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

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
            'notes' => 'nullable|string',

            'route_id' => 'nullable|exists:route_plans,id',

            'signature' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $pricePerLiter = $this->clientPricePerLiter($client);

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

        $transportReport = TransportReport::query()
            ->where('user_id', auth()->id())
            ->whereDate('date', $validated['collection_date'])
            ->latest()
            ->first();

        $collection = Collection::create([

            'client_id' => $client->id,

            'user_id' => auth()->id(),

            'transport_report_id' => $transportReport?->id,

            'collection_date' => $validated['collection_date'],

            'liters' => $validated['liters'],

            'price_per_liter' => $pricePerLiter,

            'total_price' => $validated['liters'] * $pricePerLiter,

            'payment_method' => $client->payment_method,

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
        $collection->load(['client', 'user', 'transportReport.vehicle']);

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
    public function update(
        Request $request,
        Collection $collection,
        WarehouseInventoryService $inventory
    )
    {
        $validated = $request->validate([
            'collection_date' => 'required|date',
            'liters' => 'required|numeric|min:0',
            'notes' => 'nullable|string',

            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $client = $collection->client;
        $pricePerLiter = $this->clientPricePerLiter($client);

        $availableStock = $inventory->currentStock() + (float) $collection->liters;

        if ((float) $validated['liters'] > $availableStock) {
            throw ValidationException::withMessages([
                'liters' => 'Количеството не може да бъде записано. Максимално допустимото количество е ' . number_format($availableStock, 2) . ' литра.',
            ]);
        }

        $collection->update([

            'collection_date' => $validated['collection_date'],

            'liters' => $validated['liters'],

            'price_per_liter' => $pricePerLiter,

            'total_price' => $validated['liters'] * $pricePerLiter,

            'payment_method' => $client->payment_method,

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
     * Return the client's configured rate, or stop before a nullable client
     * value can be inserted into collections.price_per_liter.
     */
    private function clientPricePerLiter(Client $client): float
    {
        if ($client->price_per_liter === null) {
            throw ValidationException::withMessages([
                'price_per_liter' => 'Client price per liter is not configured.',
            ]);
        }

        return (float) $client->price_per_liter;
    }

    /**
     * Изтриване
     */
    public function destroy(Collection $collection, WarehouseInventoryService $inventory)
    {
        if ($inventory->currentStock() - (float) $collection->liters < 0) {
            return back()->with(
                'error',
                'Събирането не може да бъде изтрито, защото част от количеството вече е предадено от склада.'
            );
        }

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

    /**
     * Генерирай приемо-предавателен протокол за печат.
     */
    public function pdf(Collection $collection, ProtocolService $protocols)
    {
        $protocol = $protocols->generate($collection);

        return response(Storage::disk('public')->get($protocol->pdf_path), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="protocol-' . $collection->id . '.pdf"',
        ]);
    }
}
