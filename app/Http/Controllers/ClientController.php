<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Покажи всички обекти
     */
    public function index(Request $request)
    {
        $query = Client::query();

        if ($request->filled('search')) {

            $query->where(function ($q) use ($request) {

                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('address', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');

            });

        }

        $clients = $query
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('clients.index', compact('clients'));
    }

    /**
     * Форма за нов обект
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Запази нов обект
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',

            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',

            'phone' => 'nullable|string|max:30',
            'contact_person' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',

        ]);

        Client::create($validated);

        return redirect()
            ->route('clients.index')
            ->with('success', 'Обектът беше добавен успешно.');
    }

    /**
     * Детайли за обект
     */
    public function show(Client $client)
    {
        $collections = $client->collections()
            ->latest('collection_date')
            ->get();

        $totalLiters = $collections->sum('liters');
        $totalRevenue = $collections->sum('total_price');

        return view('clients.show', compact(
            'client',
            'collections',
            'totalLiters',
            'totalRevenue'
        ));
    }

    /**
     * Редакция
     */
    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    /**
     * Обновяване
     */
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([

            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',

            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',

            'phone' => 'nullable|string|max:30',
            'contact_person' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',

        ]);

        $client->update($validated);

        return redirect()
            ->route('clients.index')
            ->with('success', 'Обектът беше обновен успешно.');
    }

    /**
     * Изтриване
     */
    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()
            ->route('clients.index')
            ->with('success', 'Обектът беше изтрит успешно.');
    }
}