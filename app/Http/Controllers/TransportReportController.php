<?php

namespace App\Http\Controllers;

use App\Models\TransportReport;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TransportReportController extends Controller
{
    /**
     * Начало на работния ден.
     */
    public function create()
    {
        $todayReport = TransportReport::where('user_id', Auth::id())
            ->whereDate('date', today())
            ->first();

        if ($todayReport) {

            return redirect()->route('dashboard');

        }

        $vehicles = Vehicle::orderBy('registration_number')->get();

        return view('transport_reports.create', compact('vehicles'));
    }

    /**
     * Записва началото на деня.
     */
    public function store(Request $request)
    {
        $request->validate([

            'vehicle_id' => 'required|exists:vehicles,id',

            'start_km' => 'required|integer|min:0',

            'start_fuel' => 'required|integer|min:0|max:100',

            'notes' => 'nullable|string|max:1000',

        ]);

        TransportReport::create([

            'user_id' => Auth::id(),

            'vehicle_id' => $request->vehicle_id,

            'date' => today(),

            'start_km' => $request->start_km,

            'start_fuel' => $request->start_fuel,

            'notes' => $request->notes,

        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Работният ден беше започнат успешно.');
    }

    /**
     * Форма за край на работния ден.
     */
    public function edit()
    {
        $report = TransportReport::where('user_id', Auth::id())
            ->whereDate('date', today())
            ->firstOrFail();

        return view('transport_reports.end', compact('report'));
    }

    /**
     * Записва края на работния ден.
     */
    public function update(Request $request)
    {
        $request->validate([

            'end_km' => 'required|integer|min:0',

            'end_fuel' => 'required|integer|min:0|max:100',

            'receipt' => 'nullable|image|max:4096',

            'notes' => 'nullable|string|max:1000',

        ]);

        $report = TransportReport::where('user_id', Auth::id())
            ->whereDate('date', today())
            ->firstOrFail();

        if ($request->hasFile('receipt')) {

            $path = $request->file('receipt')->store(
                'receipts',
                'public'
            );

            $report->receipt = $path;
        }

        $report->end_km = $request->end_km;

        $report->end_fuel = $request->end_fuel;

        $report->notes = $request->notes;

        $report->save();

        return redirect()
            ->route('dashboard')
            ->with('success', 'Работният ден беше приключен успешно.');
    }
}