<x-app-layout>

<x-slot name="header">

<div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">

    <div>

        <h2 class="text-3xl font-bold">

            🚛 Маршрут

        </h2>

        <p class="text-gray-500">

            {{ \Carbon\Carbon::parse($route->route_date)->format('d.m.Y') }}

        </p>

    </div>

    <div class="flex flex-wrap gap-3">

        <a
            href="{{ route('routes.drive',$route) }}"
            class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-semibold shadow">

            ▶️ Започни маршрут

        </a>

        <a
            href="{{ route('routes.index') }}"
            class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-xl">

            ← Назад

        </a>

    </div>

</div>

</x-slot>

<div class="space-y-6">

@if(session('success'))

<div class="bg-green-500 text-white rounded-xl p-4 shadow">

{{ session('success') }}

</div>

@endif

@php

$total = $route->clients->count();
$visited = $route->clients->where('pivot.visited',true)->count();
$percent = $total ? round(($visited/$total)*100) : 0;

@endphp

<div class="bg-white rounded-2xl shadow-lg p-6">

<div class="flex justify-between mb-3">

<div class="font-bold">

Прогрес на маршрута

</div>

<div>

{{ $visited }} / {{ $total }}

</div>

</div>

<div class="w-full h-4 bg-gray-200 rounded-full overflow-hidden">

<div
class="bg-green-500 h-4"
style="width: {{ $percent }}%">

</div>

</div>

<div class="text-sm text-gray-500 mt-2">

{{ $percent }}% изпълнен

</div>

</div>

<div class="grid grid-cols-2 xl:grid-cols-4 gap-4">

<div class="bg-white rounded-2xl shadow-lg p-6">

<div class="text-gray-500">

📅 Дата

</div>

<div class="text-2xl font-bold mt-2">

{{ \Carbon\Carbon::parse($route->route_date)->format('d.m.Y') }}

</div>

</div>

<div class="bg-white rounded-2xl shadow-lg p-6">

<div class="text-gray-500">

🚛 Шофьор

</div>

<div class="text-2xl font-bold mt-2">

{{ $route->driver ?: '-' }}

</div>

</div>

<div class="bg-white rounded-2xl shadow-lg p-6">

<div class="text-gray-500">

📋 Статус

</div>

<div class="mt-3">

@if($route->status=='planned')

<span class="bg-yellow-100 text-yellow-700 px-4 py-2 rounded-full">

Планиран

</span>

@elseif($route->status=='in_progress')

<span class="bg-blue-100 text-blue-700 px-4 py-2 rounded-full">

В процес

</span>

@elseif($route->status=='completed')

<span class="bg-green-100 text-green-700 px-4 py-2 rounded-full">

Завършен

</span>

@else

<span class="bg-red-100 text-red-700 px-4 py-2 rounded-full">

Отменен

</span>

@endif

</div>

</div>

<div class="bg-white rounded-2xl shadow-lg p-6">

<div class="text-gray-500">

🏢 Обекти

</div>

<div class="text-2xl font-bold mt-2">

{{ $total }}

</div>

</div>

</div>
<!-- Desktop -->

<div class="hidden lg:block bg-white rounded-2xl shadow-lg overflow-hidden">

    <table class="w-full">

        <thead class="bg-slate-100">

        <tr>

            <th class="px-6 py-4 text-left">#</th>
            <th class="px-6 py-4 text-left">Обект</th>
            <th class="px-6 py-4 text-left">Телефон</th>
            <th class="px-6 py-4 text-center">Статус</th>
            <th class="px-6 py-4 text-center">Действия</th>

        </tr>

        </thead>

        <tbody>

        @forelse($route->clients as $client)

        <tr class="border-t hover:bg-slate-50">

            <td class="px-6 py-5">

                {{ $client->pivot->position }}

            </td>

            <td class="px-6 py-5">

                <div class="font-semibold">

                    {{ $client->name }}

                </div>

                <div class="text-gray-500 text-sm">

                    {{ $client->address }}

                </div>

            </td>

            <td class="px-6 py-5">

                @if($client->phone)

                <a
                    href="tel:{{ $client->phone }}"
                    class="text-blue-600">

                    {{ $client->phone }}

                </a>

                @else

                -

                @endif

            </td>

            <td class="text-center">

                @if($client->pivot->visited)

                    <span class="bg-green-100 text-green-700 px-3 py-2 rounded-full">

                        ✔️ Посетен

                    </span>

                @else

                    <span class="bg-yellow-100 text-yellow-700 px-3 py-2 rounded-full">

                        ⏳ Чака

                    </span>

                @endif

            </td>

            <td>

                <div class="flex justify-center gap-2">

                    <a
                        href="{{ route('clients.show',$client) }}"
                        class="bg-blue-600 text-white w-10 h-10 rounded-lg flex items-center justify-center">

                        👁️

                    </a>

                    <a
                        href="https://www.google.com/maps/dir/?api=1&destination={{ urlencode($client->address) }}"
                        target="_blank"
                        class="bg-indigo-600 text-white w-10 h-10 rounded-lg flex items-center justify-center">

                        🧭

                    </a>

                    <a
                        href="{{ route('collections.create',$client) }}"
                        class="bg-green-600 text-white w-10 h-10 rounded-lg flex items-center justify-center">

                        🛢️

                    </a>

                </div>

            </td>

        </tr>

        @empty

        <tr>

            <td colspan="5" class="py-12 text-center text-gray-500">

                Няма добавени обекти.

            </td>

        </tr>

        @endforelse

        </tbody>

    </table>

</div>

<!-- Mobile -->

<div class="lg:hidden space-y-4">

@foreach($route->clients as $client)

<div class="bg-white rounded-2xl shadow-lg p-5">

<div class="flex justify-between">

<div>

<div class="text-xl font-bold">

🏢 {{ $client->name }}

</div>

<div class="text-gray-500 mt-2">

📍 {{ $client->address }}

</div>

@if($client->phone)

<div class="mt-2">

📞

<a
href="tel:{{ $client->phone }}"
class="text-blue-600">

{{ $client->phone }}

</a>

</div>

@endif

<div class="mt-3">

@if($client->pivot->visited)

<span class="bg-green-100 text-green-700 px-3 py-2 rounded-full">

✔️ Посетен

</span>

@else

<span class="bg-yellow-100 text-yellow-700 px-3 py-2 rounded-full">

⏳ Очаква

</span>

@endif

</div>

</div>

<div class="flex flex-col gap-2">

<a
href="https://www.google.com/maps/dir/?api=1&destination={{ urlencode($client->address) }}"
target="_blank"
class="bg-indigo-600 text-white rounded-xl px-4 py-3 text-center">

🧭

</a>

<a
href="{{ route('collections.create',$client) }}"
class="bg-green-600 text-white rounded-xl px-4 py-3 text-center">

🛢️

</a>

<a
href="{{ route('clients.show',$client) }}"
class="bg-blue-600 text-white rounded-xl px-4 py-3 text-center">

👁️

</a>

</div>

</div>

</div>

@endforeach

@if($route->clients->isEmpty())

<div class="bg-white rounded-2xl shadow-lg p-8 text-center text-gray-500">

Няма обекти в този маршрут.

</div>

@endif

</div>

</div>

</x-app-layout>