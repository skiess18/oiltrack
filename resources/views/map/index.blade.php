<x-app-layout>

    <x-slot name="header">

        <div class="flex justify-between items-center">

            <div>

                <h2 class="text-3xl font-bold text-slate-800">
                    🗺️ Карта на обектите
                </h2>

                <p class="text-gray-500">
                    Всички обекти на една карта
                </p>

            </div>

        </div>

    </x-slot>

    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">

        <div id="map" style="height:700px;"></div>

    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>

        @if($selectedClient)

            const map = L.map('map').setView([
                {{ $selectedClient->latitude }},
                {{ $selectedClient->longitude }}
            ],16);

        @else

            const map = L.map('map').setView([
                42.7339,
                25.4858
            ],7);

        @endif

        L.tileLayer(
            'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            {
                maxZoom:19,
                attribution:'© OpenStreetMap'
            }
        ).addTo(map);
        @foreach($clients as $client)

    L.marker([
        {{ $client->latitude }},
        {{ $client->longitude }}
    ])
    .addTo(map)
    .bindPopup(`
        <div style="min-width:240px">

            <h3 style="font-size:18px;font-weight:bold;margin-bottom:8px;">
                🏢 {{ $client->name }}
            </h3>

            <p>
                📍 {{ $client->address }}
            </p>

            <p>
                📞 {{ $client->phone ?: 'Няма телефон' }}
            </p>

            <p>
                👤 {{ $client->contact_person ?: 'Няма лице за контакт' }}
            </p>

            <p>
                🛢️ Капацитет: {{ $client->capacity }} L
            </p>

            <div style="margin-top:12px;display:flex;gap:8px;flex-wrap:wrap;">

                <a
                    href="{{ route('clients.show',$client) }}"
                    style="
                        background:#2563eb;
                        color:white;
                        padding:8px 12px;
                        border-radius:8px;
                        text-decoration:none;
                    ">

                    👁️ Обект

                </a>

                <a
                    target="_blank"
                    href="https://www.google.com/maps/dir/?api=1&destination={{ $client->latitude }},{{ $client->longitude }}"
                    style="
                        background:#16a34a;
                        color:white;
                        padding:8px 12px;
                        border-radius:8px;
                        text-decoration:none;
                    ">

                    🧭 Навигация

                </a>

            </div>

        </div>
    `);

@endforeach

    </script>

</x-app-layout>