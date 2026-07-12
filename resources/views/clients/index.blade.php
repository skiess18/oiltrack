<x-app-layout>

<x-slot name="header">

<div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">

    <div>

        <h2 class="text-3xl font-bold text-slate-800">

            🏢 Обекти

        </h2>

        <p class="text-gray-500">

            Управление на всички обекти

        </p>

    </div>

    <div class="flex flex-col sm:flex-row gap-3">

        <a
            href="{{ route('map.index') }}"
            class="bg-sky-600 hover:bg-sky-700 text-white px-6 py-3 rounded-xl shadow text-center font-semibold">

            🗺️ Карта

        </a>

        <a
            href="{{ route('clients.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl shadow text-center font-semibold">

            ➕ Добави обект

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

<!-- Статистика -->

<div class="grid grid-cols-2 xl:grid-cols-4 gap-4">

<div class="bg-white rounded-2xl shadow p-5">

<div class="text-4xl">🏢</div>

<div class="text-gray-500 mt-2">

Обекти

</div>

<div class="text-3xl font-bold">

{{ $clients->total() }}

</div>

</div>

<div class="bg-white rounded-2xl shadow p-5">

<div class="text-4xl">🛢️</div>

<div class="text-gray-500 mt-2">

Капацитет

</div>

<div class="text-3xl font-bold">

{{ $clients->sum('capacity') }} L

</div>

</div>

<div class="bg-white rounded-2xl shadow p-5">

<div class="text-4xl">📞</div>

<div class="text-gray-500 mt-2">

Контакти

</div>

<div class="text-3xl font-bold">

{{ $clients->total() }}

</div>

</div>

<div class="bg-white rounded-2xl shadow p-5">

<div class="text-4xl">📍</div>

<div class="text-gray-500 mt-2">

GPS

</div>

<div class="text-3xl font-bold">

{{ $clients->whereNotNull('latitude')->count() }}

</div>

</div>

</div>

<!-- Търсене -->

<div class="bg-white rounded-2xl shadow p-6">

<form
method="GET"
action="{{ route('clients.index') }}"
class="flex flex-col lg:flex-row gap-3">

<input
type="text"
name="search"
value="{{ request('search') }}"
placeholder="🔍 Търси..."
class="flex-1 border rounded-xl px-4 py-3">

<button
class="bg-slate-700 hover:bg-slate-800 text-white rounded-xl px-6 py-3">

Търси

</button>

</form>

</div>
<!-- Desktop таблица -->

<div class="hidden lg:block bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">

    <table class="w-full">

        <thead class="bg-slate-100">

        <tr>

            <th class="text-left px-6 py-4">Име</th>
            <th class="text-left px-6 py-4">Адрес</th>
            <th class="text-left px-6 py-4">Телефон</th>
            <th class="text-left px-6 py-4">Контакт</th>
            <th class="text-center px-6 py-4">Капацитет</th>
            <th class="text-center px-6 py-4">Действия</th>

        </tr>

        </thead>

        <tbody>

        @forelse($clients as $client)

            <tr class="border-t hover:bg-slate-50">

                <td class="px-6 py-5 font-semibold">

                    <a
                        href="{{ route('clients.show',$client) }}"
                        class="text-blue-600 hover:underline">

                        {{ $client->name }}

                    </a>

                </td>

                <td>{{ $client->address }}</td>

                <td>

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

                <td>{{ $client->contact_person ?: '-' }}</td>

                <td class="text-center">

                    {{ $client->capacity }} L

                </td>

                <td>

                    <div class="flex justify-center gap-2">

                        <a
                            href="{{ route('map.index') }}?client={{ $client->id }}"
                            class="bg-sky-600 text-white w-10 h-10 rounded-lg flex items-center justify-center">

                            🗺️

                        </a>

                        <a
                            href="{{ route('collections.create',$client) }}"
                            class="bg-green-600 text-white w-10 h-10 rounded-lg flex items-center justify-center">

                            🛢️

                        </a>

                        <a
                            href="{{ route('clients.edit',$client) }}"
                            class="bg-yellow-500 text-white w-10 h-10 rounded-lg flex items-center justify-center">

                            ✏️

                        </a>

                    </div>

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="6" class="text-center py-12 text-gray-500">

                    Няма намерени обекти.

                </td>

            </tr>

        @endforelse

        </tbody>

    </table>

</div>

<!-- Мобилен изглед -->

<div class="lg:hidden space-y-4">

@foreach($clients as $client)

<div class="bg-white rounded-2xl shadow-lg p-5">

<div class="flex justify-between items-start">

<div>

<h3 class="font-bold text-xl">

<a
href="{{ route('clients.show',$client) }}"
class="text-blue-700">

🏢 {{ $client->name }}

</a>

</h3>

<p class="text-gray-600 mt-2">

📍 {{ $client->address }}

</p>

@if($client->phone)

<p class="mt-2">

📞

<a
href="tel:{{ $client->phone }}"
class="text-blue-600">

{{ $client->phone }}

</a>

</p>

@endif

@if($client->contact_person)

<p class="mt-2">

👤 {{ $client->contact_person }}

</p>

@endif

<p class="mt-2 font-semibold">

🛢️ {{ $client->capacity }} L

</p>

</div>

<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">

Активен

</span>

</div>

<div class="grid grid-cols-2 gap-3 mt-5">
    <div>

    @if($client->latitude && $client->longitude)

        <a
            href="https://www.google.com/maps/dir/?api=1&destination={{ $client->latitude }},{{ $client->longitude }}"
            target="_blank"
            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl py-3 flex items-center justify-center font-semibold">

            🧭 Навигация

        </a>

    @else

        <a
            href="https://www.google.com/maps/search/?api=1&query={{ urlencode($client->address) }}"
            target="_blank"
            class="w-full bg-sky-600 hover:bg-sky-700 text-white rounded-xl py-3 flex items-center justify-center font-semibold">

            🗺️ Карта

        </a>

    @endif

</div>

<div>

    <a
        href="{{ route('collections.create',$client) }}"
        class="w-full bg-green-600 hover:bg-green-700 text-white rounded-xl py-3 flex items-center justify-center font-semibold">

        🛢️ Събиране

    </a>

</div>

<div>

    <a
        href="{{ route('clients.edit',$client) }}"
        class="w-full bg-yellow-500 hover:bg-yellow-600 text-white rounded-xl py-3 flex items-center justify-center font-semibold">

        ✏️ Редакция

    </a>

</div>

<div>

    <form
        action="{{ route('clients.destroy',$client) }}"
        method="POST">

        @csrf
        @method('DELETE')

        <button
            type="submit"
            onclick="return confirm('Да се изтрие ли обектът?')"
            class="w-full bg-red-600 hover:bg-red-700 text-white rounded-xl py-3 font-semibold">

            🗑️ Изтрий

        </button>

    </form>

</div>

</div>

</div>

@endforeach

@if($clients->isEmpty())

<div class="bg-white rounded-2xl shadow-lg p-10 text-center text-gray-500">

    Няма намерени обекти.

</div>

@endif

</div>

@if($clients->hasPages())

<div class="bg-white rounded-2xl shadow-lg p-5">

    {{ $clients->links() }}

</div>

@endif

</div>

</x-app-layout>