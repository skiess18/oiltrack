<x-app-layout>

<x-slot name="header">

<div class="flex flex-col xl:flex-row xl:justify-between xl:items-center gap-5">

    <div>

        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-slate-800">

            🏢 Обекти

        </h2>

        <p class="text-gray-500 mt-2">

            Управление на всички обекти

        </p>

    </div>

    <div class="flex flex-col sm:flex-row gap-3 w-full xl:w-auto">

        <a
            href="{{ route('map.index') }}"
            class="bg-sky-600 hover:bg-sky-700 hover:scale-105 transition-all duration-300 text-white text-center font-semibold px-6 py-3 rounded-2xl shadow">

            🗺️ Карта

        </a>

        <a
            href="{{ route('clients.create') }}"
            class="bg-blue-600 hover:bg-blue-700 hover:scale-105 transition-all duration-300 text-white text-center font-semibold px-6 py-3 rounded-2xl shadow">

            ➕ Добави обект

        </a>

    </div>

</div>

</x-slot>

<div class="space-y-6">

@if(session('success'))

<div class="bg-green-500 text-white rounded-2xl shadow-lg p-5">

    {{ session('success') }}

</div>

@endif
<!-- Статистика -->

<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

    <div class="bg-white rounded-3xl shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 p-6">

        <div class="flex justify-between items-center">

            <div>

                <p class="text-gray-500 text-sm">

                    Обекти

                </p>

                <h3 class="text-4xl font-bold mt-2 text-slate-800">

                    {{ $clients->total() }}

                </h3>

            </div>

            <div class="w-16 h-16 rounded-2xl bg-blue-100 flex items-center justify-center text-4xl">

                🏢

            </div>

        </div>

    </div>

    <div class="bg-white rounded-3xl shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 p-6">

        <div class="flex justify-between items-center">

            <div>

                <p class="text-gray-500 text-sm">

                    Капацитет

                </p>

                <h3 class="text-4xl font-bold mt-2 text-green-600">

                    {{ $clients->sum('capacity') }}

                </h3>

                <p class="text-gray-400 text-sm">

                    литра

                </p>

            </div>

            <div class="w-16 h-16 rounded-2xl bg-green-100 flex items-center justify-center text-4xl">

                🛢️

            </div>

        </div>

    </div>

    <div class="bg-white rounded-3xl shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 p-6">

        <div class="flex justify-between items-center">

            <div>

                <p class="text-gray-500 text-sm">

                    Контакти

                </p>

                <h3 class="text-4xl font-bold mt-2 text-orange-500">

                    {{ $clients->total() }}

                </h3>

            </div>

            <div class="w-16 h-16 rounded-2xl bg-orange-100 flex items-center justify-center text-4xl">

                📞

            </div>

        </div>

    </div>

    <div class="bg-white rounded-3xl shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 p-6">

        <div class="flex justify-between items-center">

            <div>

                <p class="text-gray-500 text-sm">

                    GPS координати

                </p>

                <h3 class="text-4xl font-bold mt-2 text-sky-600">

                    {{ $clients->whereNotNull('latitude')->count() }}

                </h3>

            </div>

            <div class="w-16 h-16 rounded-2xl bg-sky-100 flex items-center justify-center text-4xl">

                📍

            </div>

        </div>

    </div>

</div>
<!-- Търсене -->

<div class="bg-white rounded-3xl shadow-md p-6">

    <form
        method="GET"
        action="{{ route('clients.index') }}"
        class="flex flex-col lg:flex-row gap-4">

        <div class="relative flex-1">

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="🔍 Търси по име, адрес или телефон..."
                class="w-full border border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 rounded-2xl px-5 py-3 outline-none transition">

        </div>

        <button
            type="submit"
            class="bg-slate-800 hover:bg-slate-900 hover:scale-105 transition-all duration-300 text-white rounded-2xl px-8 py-3 font-semibold">

            🔍 Търси

        </button>

    </form>

</div>

<!-- Desktop таблица -->

<div class="hidden xl:block bg-white rounded-3xl shadow-md overflow-hidden">

    <table class="w-full">

        <thead class="bg-slate-100">

        <tr>

            <th class="px-6 py-5 text-left font-semibold">🏢 Обект</th>

            <th class="px-6 py-5 text-left font-semibold">📍 Адрес</th>

            <th class="px-6 py-5 text-left font-semibold">📞 Телефон</th>

            <th class="px-6 py-5 text-left font-semibold">👤 Контакт</th>

            <th class="px-6 py-5 text-center font-semibold">🛢️ Капацитет</th>

            <th class="px-6 py-5 text-center font-semibold">⚙️ Действия</th>

        </tr>

        </thead>

        <tbody>

        @forelse($clients as $client)

        <tr class="border-t hover:bg-slate-50 transition">

            <td class="px-6 py-5 font-semibold">

                <a
                    href="{{ route('clients.show',$client) }}"
                    class="text-blue-600 hover:text-blue-800">

                    {{ $client->name }}

                </a>

            </td>

            <td class="px-6 py-5">

                {{ $client->address }}

            </td>

            <td class="px-6 py-5">

                @if($client->phone)

                    <a
                        href="tel:{{ $client->phone }}"
                        class="text-blue-600 hover:underline">

                        {{ $client->phone }}

                    </a>

                @else

                    —

                @endif

            </td>

            <td class="px-6 py-5">

                {{ $client->contact_person ?: '—' }}

            </td>

            <td class="px-6 py-5 text-center font-semibold">

                {{ $client->capacity }} L

            </td>

            <td class="px-6 py-5">

                <div class="flex justify-center gap-3">

                    <a
                        href="{{ route('map.index') }}?client={{ $client->id }}"
                        class="w-11 h-11 rounded-xl bg-sky-500 hover:bg-sky-600 text-white flex items-center justify-center transition">

                        🗺️

                    </a>

                    <a
                        href="{{ route('collections.create',$client) }}"
                        class="w-11 h-11 rounded-xl bg-green-600 hover:bg-green-700 text-white flex items-center justify-center transition">

                        🛢️

                    </a>

                    <a
                        href="{{ route('clients.edit',$client) }}"
                        class="w-11 h-11 rounded-xl bg-yellow-500 hover:bg-yellow-600 text-white flex items-center justify-center transition">

                        ✏️

                    </a>

                </div>

            </td>

        </tr>

        @empty

        <tr>

            <td colspan="6" class="py-16 text-center text-gray-500">

                Няма намерени обекти.

            </td>

        </tr>

        @endforelse

        </tbody>

    </table>

</div>
<!-- Mobile Cards -->

<div class="xl:hidden space-y-5">

@forelse($clients as $client)

<div class="bg-white rounded-3xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">

    <div class="p-5">

        <div class="flex justify-between items-start gap-4">

            <div class="flex-1">

                <a
                    href="{{ route('clients.show',$client) }}"
                    class="text-xl font-bold text-slate-800 hover:text-blue-600">

                    🏢 {{ $client->name }}

                </a>

                <div class="mt-3 space-y-2 text-gray-600">

                    <div>

                        📍 {{ $client->address }}

                    </div>

                    @if($client->phone)

                    <div>

                        📞

                        <a
                            href="tel:{{ $client->phone }}"
                            class="text-blue-600">

                            {{ $client->phone }}

                        </a>

                    </div>

                    @endif

                    @if($client->contact_person)

                    <div>

                        👤 {{ $client->contact_person }}

                    </div>

                    @endif

                    <div class="font-semibold">

                        🛢️ Капацитет: {{ $client->capacity }} L

                    </div>

                </div>

            </div>

            <div>

                @if($client->latitude)

                    <span class="bg-green-100 text-green-700 text-xs font-bold px-3 py-2 rounded-full">

                        GPS

                    </span>

                @else

                    <span class="bg-red-100 text-red-700 text-xs font-bold px-3 py-2 rounded-full">

                        Няма GPS

                    </span>

                @endif

            </div>

        </div>

    </div>

    <div class="grid grid-cols-2 gap-px bg-slate-200">

        @if($client->latitude && $client->longitude)

        <a
            href="https://www.google.com/maps/dir/?api=1&destination={{ $client->latitude }},{{ $client->longitude }}"
            target="_blank"
            class="bg-indigo-600 hover:bg-indigo-700 text-white text-center py-4 font-semibold transition">

            🧭 Навигация

        </a>

        @else

        <a
            href="https://www.google.com/maps/search/?api=1&query={{ urlencode($client->address) }}"
            target="_blank"
            class="bg-sky-600 hover:bg-sky-700 text-white text-center py-4 font-semibold transition">

            🗺️ Карта

        </a>

        @endif

        <a
            href="{{ route('collections.create',$client) }}"
            class="bg-green-600 hover:bg-green-700 text-white text-center py-4 font-semibold transition">

            🛢️ Събиране

        </a>

        <a
            href="{{ route('clients.edit',$client) }}"
            class="bg-yellow-500 hover:bg-yellow-600 text-white text-center py-4 font-semibold transition">

            ✏️ Редакция

        </a>

        <form
            action="{{ route('clients.destroy',$client) }}"
            method="POST">

            @csrf
            @method('DELETE')

            <button
                onclick="return confirm('Да се изтрие ли обектът?')"
                class="w-full bg-red-600 hover:bg-red-700 text-white py-4 font-semibold transition">

                🗑️ Изтрий

            </button>

        </form>

    </div>

</div>

@empty

<div class="bg-white rounded-3xl shadow-md p-10 text-center text-gray-500">

    Няма намерени обекти.

</div>

@endforelse

</div>
@if($clients->hasPages())

<div class="bg-white rounded-3xl shadow-md p-6">

    <div class="flex justify-center">

        {{ $clients->links() }}

    </div>

</div>

@endif

</div>

<style>

tbody tr{
    transition:all .25s;
}

tbody tr:hover{
    transform:scale(1.01);
}

.client-card{
    transition:all .25s;
}

.client-card:hover{
    transform:translateY(-3px);
}

</style>

</x-app-layout>