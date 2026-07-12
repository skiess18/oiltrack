<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class MapController extends Controller
{
    /**
     * Показва картата с всички обекти
     */
    public function index(Request $request)
    {
        // Всички обекти с GPS координати
        $clients = Client::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->orderBy('name')
            ->get();

        // Ако е избран конкретен обект
        $selectedClient = null;

        if ($request->filled('client')) {
            $selectedClient = Client::whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->find($request->client);
        }

        return view('map.index', [
            'clients' => $clients,
            'selectedClient' => $selectedClient,
        ]);
    }
}