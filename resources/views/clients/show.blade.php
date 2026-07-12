<x-app-layout>

    <x-slot name="header">
        <div class="flex justify-between items-center">

            <div>
                <h2 class="text-3xl font-bold">
                    🏢 {{ $client->name }}
                </h2>

                <p class="text-gray-500 mt-1">
                    Профил на обекта
                </p>
            </div>

            <div class="flex gap-3">

                <a href="{{ route('clients.edit', $client) }}"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-3 rounded-lg">
                    ✏️ Редакция
                </a>

                <a href="{{ route('collections.create', $client) }}"
                    class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-lg">
                    ➕ Ново събиране
                </a>

            </div>

        </div>
    </x-slot>

    <div class="space-y-8">

        <!-- Карти -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

            <div class="bg-white rounded-xl shadow p-6">

                <div class="text-gray-500 mb-2">
                    📍 Адрес
                </div>

                <div class="text-lg font-semibold">
                    {{ $client->address }}
                </div>

            </div>

            <div class="bg-white rounded-xl shadow p-6">

                <div class="text-gray-500 mb-2">
                    👤 Контакт
                </div>

                <div class="font-semibold">
                    {{ $client->contact_person ?: '-' }}
                </div>

                <div class="text-gray-500 mt-2">
                    📞 {{ $client->phone ?: '-' }}
                </div>

            </div>

            <div class="bg-white rounded-xl shadow p-6">

                <div class="text-gray-500 mb-2">
                    🛢️ Общо литри
                </div>

                <div class="text-4xl font-bold text-blue-600">
                    {{ number_format($totalLiters,2) }}
                </div>

                <div class="text-gray-500">
                    литра
                </div>

            </div>

            <div class="bg-white rounded-xl shadow p-6">

                <div class="text-gray-500 mb-2">
                    💰 Общ приход
                </div>

                <div class="text-4xl font-bold text-green-600">
                    {{ number_format($totalRevenue,2) }}
                </div>

                <div class="text-gray-500">
                    лева
                </div>

            </div>

        </div>

        <!-- История -->
        <div class="bg-white rounded-xl shadow overflow-hidden">

            <div class="px-6 py-5 border-b">

                <h2 class="text-2xl font-bold">
                    📋 История на събиранията
                </h2>

            </div>

            <table class="min-w-full">

                <thead class="bg-slate-100">

                    <tr>

                        <th class="px-6 py-4 text-left">Дата</th>
                        <th class="px-6 py-4 text-center">Литри</th>
                        <th class="px-6 py-4 text-center">Цена / литър</th>
                        <th class="px-6 py-4 text-center">Обща сума</th>
                        <th class="px-6 py-4 text-left">Бележка</th>

                    </tr>

                </thead>

                <tbody>

                @forelse($collections as $collection)

                    <tr class="border-t hover:bg-slate-50">

                        <td class="px-6 py-4">
                            {{ \Carbon\Carbon::parse($collection->collection_date)->format('d.m.Y') }}
                        </td>

                        <td class="px-6 py-4 text-center font-semibold">
                            {{ number_format($collection->liters,2) }} L
                        </td>

                        <td class="px-6 py-4 text-center">
                            {{ number_format($collection->price_per_liter,2) }} лв
                        </td>

                        <td class="px-6 py-4 text-center font-bold text-green-600">
                            {{ number_format($collection->total_price,2) }} лв
                        </td>

                        <td class="px-6 py-4">
                            {{ $collection->notes ?: '-' }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="5" class="py-12 text-center text-gray-500">

                            Все още няма събирания за този обект.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</x-app-layout>