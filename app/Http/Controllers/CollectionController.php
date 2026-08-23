<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Collection;
use App\Models\RoutePlan;
use App\Models\TransportReport;
use App\Services\WarehouseInventoryService;
use App\Services\ProtocolService;
use App\Services\NotificationSettingsService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
    public function store(
        Request $request,
        Client $client,
        ProtocolService $protocols,
        NotificationSettingsService $notificationSettings
    )
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

        $emailNotice = $this->sendCollectionDocument($collection, $protocols, $notificationSettings);

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
                            'Събирането беше записано успешно.' . $emailNotice
                        );
                }

                $route->update([
                    'status' => 'completed',
                ]);

                return redirect()
                    ->route('routes.show', $route)
                    ->with(
                        'success',
                        '🎉 Маршрутът беше завършен успешно.' . $emailNotice
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
                'Събирането беше добавено успешно.' . $emailNotice
            );
    }

    private function sendCollectionDocument(
        Collection $collection,
        ProtocolService $protocols,
        NotificationSettingsService $notificationSettings
    ): string {
        try {
            $protocol = $protocols->generate($collection);
            $collection->load('client.emailRecipients');

            $adminRecipients = $notificationSettings->recipientsFor(NotificationSettingsService::COLLECTION_COMPLETED);
            $clientRecipients = $collection->client->emailRecipients
                ->pluck('email')
                ->filter(fn ($email) => filter_var($email, FILTER_VALIDATE_EMAIL))
                ->map(fn ($email) => strtolower(trim($email)))
                ->unique()
                ->values()
                ->all();
            $recipients = collect($adminRecipients)->merge($clientRecipients)->unique()->values()->all();

            if ($recipients === []) {
                Log::warning('Collection document was not emailed because no valid recipients are configured.', [
                    'collection_id' => $collection->id,
                    'client_id' => $collection->client_id,
                ]);

                return ' Документът е генериран, но няма валидни email получатели.';
            }

            Mail::to($recipients)->send(new \App\Mail\WasteCollectionProtocolMail($protocol));

            $protocol->forceFill([
                'email_sent_to_owner' => $adminRecipients !== [],
                'email_sent_to_client' => $clientRecipients !== [],
                'sent_at' => now(),
            ])->save();

            if ($clientRecipients === []) {
                Log::info('Collection document emailed only to administrators because client has no configured email.', [
                    'collection_id' => $collection->id,
                    'client_id' => $collection->client_id,
                    'recipients' => $adminRecipients,
                ]);

                return ' Документът е изпратен само до Admin получателите, защото обектът няма email.';
            }

            return ' Документът е изпратен по email.';
        } catch (\Throwable $exception) {
            Log::error('Collection document email failed after the collection was saved.', [
                'collection_id' => $collection->id,
                'client_id' => $collection->client_id,
                'exception' => $exception,
            ]);

            return ' Документът не беше изпратен по email; грешката е записана в системния log.';
        }
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
