<x-app-layout>

<x-slot name="header">

<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

    <div>

        <h2 class="text-2xl lg:text-4xl font-bold text-slate-800">

            📊 Dashboard

        </h2>

        <p class="text-gray-500 mt-1">

            Управление на събирането на използвано олио

        </p>

    </div>

    <div class="flex flex-col sm:flex-row gap-3">

        <a
            href="{{ route('clients.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white text-center px-5 py-3 rounded-xl shadow transition-all hover:scale-105">

            ➕ Нов обект

        </a>

        <a
            href="{{ route('routes.create') }}"
            class="bg-green-600 hover:bg-green-700 text-white text-center px-5 py-3 rounded-xl shadow transition-all hover:scale-105">

            🚛 Нов маршрут

        </a>

    </div>

</div>

</x-slot>

<div class="space-y-6">

<div class="bg-gradient-to-r from-slate-900 to-blue-700 rounded-3xl text-white p-6 lg:p-8 shadow-xl">

    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">

        <div>

            <h1 class="text-3xl lg:text-5xl font-bold">

                👋 Здравей,

                {{ Auth::user()->name }}

            </h1>

            <p class="mt-3 text-blue-100">

                Добре дошъл в OilTrack CRM

            </p>

        </div>

        <div class="text-left lg:text-right">

            <div class="text-sm opacity-80">

                Днес

            </div>

            <div class="text-2xl font-bold">

                {{ now()->format('d.m.Y') }}

            </div>

        </div>

    </div>

</div>

<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

<div class="bg-white rounded-3xl shadow-md hover:shadow-xl transition-all p-6">

<div class="flex justify-between items-center">

<div>

<div class="text-gray-500">

Обекти

</div>

<div class="text-4xl font-bold mt-2">

{{ $clientsCount }}

</div>

</div>

<div class="text-5xl">

🏢

</div>

</div>

</div>

<div class="bg-white rounded-3xl shadow-md hover:shadow-xl transition-all p-6">

<div class="flex justify-between items-center">

<div>

<div class="text-gray-500">

Общо литри

</div>

<div class="text-4xl font-bold mt-2 text-green-600">

{{ number_format($totalLiters,2) }}

</div>

<div class="text-sm text-gray-400">

литра

</div>

</div>

<div class="text-5xl">

🛢️

</div>

</div>

</div>
<div class="bg-white rounded-3xl shadow-md hover:shadow-xl transition-all p-6">

    <div class="flex justify-between items-center">

        <div>

            <div class="text-gray-500">

                Общ приход

            </div>

            <div class="text-4xl font-bold mt-2 text-blue-600">

                {{ number_format($totalRevenue,2) }}

            </div>

            <div class="text-sm text-gray-400">

                лв.

            </div>

        </div>

        <div class="text-5xl">

            💰

        </div>

    </div>

</div>

<div class="bg-white rounded-3xl shadow-md hover:shadow-xl transition-all p-6">

    <div class="flex justify-between items-center">

        <div>

            <div class="text-gray-500">

                Събирания

            </div>

            <div class="text-4xl font-bold mt-2">

                {{ $collectionsCount }}

            </div>

        </div>

        <div class="text-5xl">

            📋

        </div>

    </div>

</div>

</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <div class="bg-white rounded-3xl shadow-md hover:shadow-xl transition-all p-6">

        <div class="text-gray-500 font-semibold">

            🛢️ Днес събрано

        </div>

        <div class="text-5xl font-bold text-green-600 mt-4">

            {{ number_format($todayLiters,2) }}

        </div>

        <div class="text-gray-400 mt-2">

            литра

        </div>

    </div>

    <div class="bg-white rounded-3xl shadow-md hover:shadow-xl transition-all p-6">

        <div class="text-gray-500 font-semibold">

            💰 Приход днес

        </div>

        <div class="text-5xl font-bold text-blue-600 mt-4">

            {{ number_format($todayRevenue,2) }}

        </div>

        <div class="text-gray-400 mt-2">

            лева

        </div>

    </div>

    <div class="bg-white rounded-3xl shadow-md hover:shadow-xl transition-all p-6">

        <div class="text-gray-500 font-semibold">

            🚛 Днешни маршрути

        </div>

        <div class="text-5xl font-bold text-orange-500 mt-4">

            {{ $todayRoutes }}

        </div>

    </div>

</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

    <div class="bg-white rounded-3xl shadow-md p-6">

        <div class="flex justify-between items-center mb-6">

            <h2 class="text-2xl font-bold">

                🚛 Маршрути

            </h2>

        </div>

        <div class="space-y-5">

            <div class="flex justify-between items-center">

                <span>Активни</span>

                <span class="bg-blue-100 text-blue-700 px-4 py-2 rounded-full font-bold">

                    {{ $activeRoutes }}

                </span>

            </div>

            <div class="flex justify-between items-center">

                <span>Завършени</span>

                <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full font-bold">

                    {{ $completedRoutes }}

                </span>

            </div>

            <div class="flex justify-between items-center">

                <span>Днес</span>

                <span class="bg-orange-100 text-orange-700 px-4 py-2 rounded-full font-bold">

                    {{ $todayRoutes }}

                </span>

            </div>

        </div>

    </div>
    <div class="bg-white rounded-3xl shadow-md p-6">

    <div class="flex justify-between items-center mb-6">

        <h2 class="text-2xl font-bold">

            ⚡ Бързи действия

        </h2>

    </div>

    <div class="grid grid-cols-2 gap-4">

        <a
            href="{{ route('clients.create') }}"
            class="bg-blue-600 hover:bg-blue-700 hover:scale-105 transition-all duration-300 text-white rounded-2xl p-6 text-center shadow">

            <div class="text-4xl mb-3">➕</div>

            <div class="font-semibold">

                Нов обект

            </div>

        </a>

        <a
            href="{{ route('routes.create') }}"
            class="bg-green-600 hover:bg-green-700 hover:scale-105 transition-all duration-300 text-white rounded-2xl p-6 text-center shadow">

            <div class="text-4xl mb-3">🚛</div>

            <div class="font-semibold">

                Нов маршрут

            </div>

        </a>

        <a
            href="{{ route('clients.index') }}"
            class="bg-indigo-600 hover:bg-indigo-700 hover:scale-105 transition-all duration-300 text-white rounded-2xl p-6 text-center shadow">

            <div class="text-4xl mb-3">🏢</div>

            <div class="font-semibold">

                Обекти

            </div>
            @if(auth()->user()->isDriver())

    @if($todayTransportReport && !$todayTransportReport->end_km)

        <div class="mt-6">

            <a
                href="{{ route('transport-report.edit') }}"
                class="w-full flex items-center justify-center bg-red-600 hover:bg-red-700 text-white py-4 rounded-2xl text-lg font-bold shadow-lg transition">

                🏁 Приключи работния ден

            </a>

        </div>

    @endif

@endif

        </a>

        <a
            href="{{ route('map.index') }}"
            class="bg-sky-600 hover:bg-sky-700 hover:scale-105 transition-all duration-300 text-white rounded-2xl p-6 text-center shadow">

            <div class="text-4xl mb-3">🗺️</div>

            <div class="font-semibold">

                Карта

            </div>

        </a>

    </div>

</div>

</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

    <!-- Последни събирания -->

    <div class="bg-white rounded-3xl shadow-md overflow-hidden">

        <div class="px-6 py-5 border-b bg-slate-50">

            <h2 class="text-2xl font-bold">

                🛢️ Последни събирания

            </h2>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-slate-100">

                    <tr>

                        <th class="px-5 py-4 text-left">

                            Обект

                        </th>

                        <th class="px-5 py-4 text-center">

                            Литри

                        </th>

                        <th class="px-5 py-4 text-center">

                            Сума

                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($latestCollections as $collection)

                    <tr class="border-t hover:bg-slate-50 transition">

                        <td class="px-5 py-4 font-semibold">

                            {{ $collection->client->name }}

                        </td>

                        <td class="px-5 py-4 text-center">

                            {{ number_format($collection->liters,2) }}

                        </td>

                        <td class="px-5 py-4 text-center font-bold text-green-600">

                            {{ number_format($collection->total_price,2) }} лв.

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="3" class="py-10 text-center text-gray-500">

                            Все още няма събирания.

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>
        <!-- Нови обекти -->

    <div class="bg-white rounded-3xl shadow-md overflow-hidden">

        <div class="px-6 py-5 border-b bg-slate-50">

            <h2 class="text-2xl font-bold">

                🏢 Нови обекти

            </h2>

        </div>

        <div class="divide-y">

            @forelse($latestClients as $client)

                <a
                    href="{{ route('clients.show', $client) }}"
                    class="block px-6 py-5 hover:bg-slate-50 transition">

                    <div class="flex justify-between items-center">

                        <div>

                            <div class="font-bold text-lg">

                                {{ $client->name }}

                            </div>

                            <div class="text-gray-500 mt-1">

                                📍 {{ $client->address }}

                            </div>

                        </div>

                        <div class="text-blue-600 text-xl">

                            →

                        </div>

                    </div>

                </a>

            @empty

                <div class="py-12 text-center text-gray-500">

                    Няма добавени обекти.

                </div>

            @endforelse

        </div>

    </div>

</div>

</div>

</x-app-layout>