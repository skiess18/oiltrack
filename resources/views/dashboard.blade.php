<x-app-layout>

<x-slot name="header">

<div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">

    <div>

        <h2 class="text-3xl font-bold text-slate-800">

            📊 Dashboard

        </h2>

        <p class="text-gray-500">

            Управление на събирането на използвано олио

        </p>

    </div>

    <div class="flex gap-3 flex-wrap">

        <a
            href="{{ route('clients.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl shadow">

            ➕ Нов обект

        </a>

        <a
            href="{{ route('routes.create') }}"
            class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl shadow">

            🚛 Нов маршрут

        </a>

    </div>

</div>

</x-slot>

<div class="space-y-8">

<div>

<h1 class="text-4xl font-bold">

👋 Добре дошъл,

<span class="text-blue-600">

{{ Auth::user()->name }}

</span>

</h1>

<p class="text-gray-500 mt-2">

Добре дошъл в OilTrack CRM

</p>

</div>

<div class="grid grid-cols-2 xl:grid-cols-4 gap-5">

<div class="bg-white rounded-2xl shadow-lg p-6">

<div class="text-5xl">

🏢

</div>

<div class="text-gray-500 mt-3">

Обекти

</div>

<div class="text-4xl font-bold mt-2">

{{ $clientsCount }}

</div>

</div>

<div class="bg-white rounded-2xl shadow-lg p-6">

<div class="text-5xl">

🛢️

</div>

<div class="text-gray-500 mt-3">

Общо олио

</div>

<div class="text-4xl font-bold mt-2">

{{ number_format($totalLiters,2) }}

</div>

<div class="text-sm text-gray-400">

литра

</div>

</div>

<div class="bg-white rounded-2xl shadow-lg p-6">

<div class="text-5xl">

💰

</div>

<div class="text-gray-500 mt-3">

Общ приход

</div>

<div class="text-4xl font-bold mt-2">

{{ number_format($totalRevenue,2) }}

</div>

<div class="text-sm text-gray-400">

лв.

</div>

</div>

<div class="bg-white rounded-2xl shadow-lg p-6">

<div class="text-5xl">

📋

</div>

<div class="text-gray-500 mt-3">

Събирания

</div>

<div class="text-4xl font-bold mt-2">

{{ $collectionsCount }}

</div>

</div>

</div>

<div class="grid lg:grid-cols-3 gap-6">

<div class="bg-white rounded-2xl shadow-lg p-6">

<div class="text-lg font-bold">

🛢️ Днес

</div>

<div class="text-5xl font-bold text-green-600 mt-5">

{{ number_format($todayLiters,2) }}

</div>

<div class="text-gray-500">

литра

</div>

</div>

<div class="bg-white rounded-2xl shadow-lg p-6">

<div class="text-lg font-bold">

💰 Днес

</div>

<div class="text-5xl font-bold text-blue-600 mt-5">

{{ number_format($todayRevenue,2) }}

</div>

<div class="text-gray-500">

лева

</div>

</div>

<div class="bg-white rounded-2xl shadow-lg p-6">

<div class="text-lg font-bold">

🚛 Днешни маршрути

</div>

<div class="text-5xl font-bold text-orange-500 mt-5">

{{ $todayRoutes }}

</div>

</div>
<div class="grid lg:grid-cols-2 gap-6">

    <div class="bg-white rounded-2xl shadow-lg p-6">

        <div class="flex justify-between items-center mb-6">

            <h2 class="text-2xl font-bold">

                🚛 Маршрути

            </h2>

        </div>

        <div class="space-y-4">

            <div class="flex justify-between items-center">

                <span>Активни</span>

                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full font-bold">

                    {{ $activeRoutes }}

                </span>

            </div>

            <div class="flex justify-between items-center">

                <span>Завършени</span>

                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full font-bold">

                    {{ $completedRoutes }}

                </span>

            </div>

            <div class="flex justify-between items-center">

                <span>Днес</span>

                <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full font-bold">

                    {{ $todayRoutes }}

                </span>

            </div>

        </div>

    </div>

    <div class="bg-white rounded-2xl shadow-lg p-6">

        <div class="flex justify-between items-center mb-6">

            <h2 class="text-2xl font-bold">

                ⚡ Бързи действия

            </h2>

        </div>

        <div class="grid grid-cols-2 gap-4">

            <a
                href="{{ route('clients.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white rounded-xl p-5 text-center">

                ➕<br>

                Нов обект

            </a>

            <a
                href="{{ route('routes.create') }}"
                class="bg-green-600 hover:bg-green-700 text-white rounded-xl p-5 text-center">

                🚛<br>

                Нов маршрут

            </a>

            <a
                href="{{ route('clients.index') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl p-5 text-center">

                🏢<br>

                Обекти

            </a>

            <a
                href="{{ route('map.index') }}"
                class="bg-sky-600 hover:bg-sky-700 text-white rounded-xl p-5 text-center">

                🗺️<br>

                Карта

            </a>

        </div>

    </div>

</div>
<div class="grid lg:grid-cols-2 gap-6">

    <!-- Последни събирания -->

    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

        <div class="px-6 py-5 border-b">

            <h2 class="text-2xl font-bold">

                🛢️ Последни събирания

            </h2>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-slate-100">

                    <tr>

                        <th class="px-5 py-3 text-left">Обект</th>
                        <th class="px-5 py-3 text-center">Литри</th>
                        <th class="px-5 py-3 text-center">Сума</th>

                    </tr>

                </thead>

                <tbody>

                @forelse($latestCollections as $collection)

                    <tr class="border-t hover:bg-slate-50">

                        <td class="px-5 py-4">

                            {{ $collection->client->name }}

                        </td>

                        <td class="px-5 py-4 text-center">

                            {{ number_format($collection->liters,2) }}

                        </td>

                        <td class="px-5 py-4 text-center font-semibold text-green-600">

                            {{ number_format($collection->total_price,2) }} лв.

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="3" class="text-center py-10 text-gray-500">

                            Все още няма събирания.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

    <!-- Последно добавени обекти -->

    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

        <div class="px-6 py-5 border-b">

            <h2 class="text-2xl font-bold">

                🏢 Нови обекти

            </h2>

        </div>

        <div class="divide-y">

            @forelse($latestClients as $client)

                <a
                    href="{{ route('clients.show',$client) }}"
                    class="block px-6 py-4 hover:bg-slate-50">

                    <div class="font-semibold">

                        {{ $client->name }}

                    </div>

                    <div class="text-sm text-gray-500 mt-1">

                        {{ $client->address }}

                    </div>

                </a>

            @empty

                <div class="px-6 py-10 text-center text-gray-500">

                    Няма добавени обекти.

                </div>

            @endforelse

        </div>

    </div>

</div>

</div>

</x-app-layout>