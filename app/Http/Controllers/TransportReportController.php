<?php

namespace App\Http\Controllers;

use App\Mail\AccountingReportMail;
use App\Mail\TransportReportMail;
use App\Mail\WasteCollectionProtocolMail;
use App\Models\Collection;
use App\Models\TransportReport;
use App\Models\Vehicle;
use App\Services\ProtocolService;
use App\Services\NotificationSettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TransportReportController extends Controller
{
    public function create()
    {
        $activeReport = TransportReport::where('user_id', Auth::id())
            ->whereNull('end_km')
            ->first();

        if ($activeReport) {
            return redirect()->route('transport-report.edit');
        }

        if (auth()->user()->isDriver()) {
            $vehicles = Vehicle::where('driver_id', auth()->id())
                ->orderBy('registration')
                ->get();
        } else {
            $vehicles = Vehicle::orderBy('registration')
                ->get();
        }

        return view('transport_reports.create', compact('vehicles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'start_km'   => 'required|integer|min:0',
            'start_fuel' => 'required|integer|min:0|max:100',
            'notes'      => 'nullable|string|max:1000',
        ]);

        $activeReport = TransportReport::where('user_id', Auth::id())
            ->whereNull('end_km')
            ->first();

        if ($activeReport) {
            return redirect()->route('transport-report.edit');
        }

        $vehicle = Vehicle::findOrFail($request->vehicle_id);

        if ((int)$request->start_km !== (int)$vehicle->current_km) {
            return back()
                ->withInput()
                ->withErrors([
                    'start_km' => 'Началните километри трябва да съвпадат с текущите километри на автомобила (' . $vehicle->current_km . ' км).'
                ]);
        }

        TransportReport::create([
            'user_id'    => Auth::id(),
            'vehicle_id' => $request->vehicle_id,
            'date'       => now(),
            'start_km'   => $request->start_km,
            'start_fuel' => $request->start_fuel,
            'notes'      => $request->notes,
        ]);

        return redirect()
            ->route('routes.index')
            ->with('success', 'Работният ден беше започнат успешно.');
    }

    public function edit()
    {
        $report = TransportReport::where('user_id', Auth::id())
            ->whereNull('end_km')
            ->firstOrFail();

        return view('transport_reports.end', compact('report'));
    }
        public function update(Request $request, ProtocolService $protocols, NotificationSettingsService $settings)
{
    $request->validate([
        'end_km'   => 'required|integer|min:0',
        'end_fuel' => 'required|integer|min:0|max:100',
        'receipt'  => 'nullable|image|max:4096',
        'notes'    => 'nullable|string|max:1000',
    ]);

    $report = TransportReport::where('user_id', Auth::id())
        ->whereNull('end_km')
        ->firstOrFail();

    if ($request->end_km < $report->start_km) {
        return back()
            ->withInput()
            ->withErrors([
                'end_km' => 'Крайните километри не могат да бъдат по-малки от началните.'
            ]);
    }

    if ($request->hasFile('receipt')) {
        $report->receipt = $request->file('receipt')->store('receipts', 'public');
    }

    $report->end_km = $request->end_km;
    $report->end_fuel = $request->end_fuel;

    if ($request->filled('notes')) {
        $report->notes = $request->notes;
    }

    $report->save();

    $vehicle = Vehicle::find($report->vehicle_id);

    if ($vehicle) {
        $vehicle->current_km = $report->end_km;
        $vehicle->save();
    }

    $collections = Collection::with('client')
        ->where('user_id', Auth::id())
        ->whereDate('collection_date', today())
        ->get();

    $totalLiters = $collections->sum('liters');
    $totalAmount = $collections->sum('total_price');

    $transportRecipients = $settings->recipientsFor(NotificationSettingsService::TRANSPORT_REPORT);
    if ($transportRecipients !== []) {
        Mail::to($transportRecipients)->send(new TransportReportMail($report));
    }

    $documentRecipients = $settings->recipientsFor(NotificationSettingsService::END_OF_DAY_DOCUMENTS);
    if ($documentRecipients !== []) {
        Mail::to($documentRecipients)->send(new AccountingReportMail(
            $report,
            $collections,
            $totalLiters,
            $totalAmount
        ));
    }

    foreach ($collections->unique('client_id') as $collection) {
        $protocol = $protocols->generate($collection);

        $collection->load('client.emailRecipients');
        $clientRecipients = $collection->client->emailRecipients->pluck('email')->all();
        $recipients = collect($documentRecipients)->merge($clientRecipients)->unique()->values()->all();

        if ($recipients !== []) {
            Mail::to($recipients)->send(new WasteCollectionProtocolMail($protocol));
            $protocol->email_sent_to_owner = $documentRecipients !== [];
            $protocol->email_sent_to_client = $clientRecipients !== [];
        }
        $protocol->sent_at = now();
        $protocol->save();
    }

        return redirect()
        ->route('dashboard')
        ->with('success', 'Работният ден беше приключен успешно.');
    }
}
