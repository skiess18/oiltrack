<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            Събирания - {{ $client->name }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 bg-green-500 text-white p-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-6">

                <div class="flex justify-between items-center mb-6">

                    <h1 class="text-2xl font-bold">
                        История на събиранията
                    </h1>

                    <a href="{{ route('collections.create', $client) }}"
                       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                        🛢️ Ново събиране
                    </a>

                </div>

                <table class="w-full border-collapse">

                    <thead>

                        <tr class="border-b">
                            <th class="text-left p-3">Дата</th>
                            <th class="text-left p-3">Литри</th>
                            <th class="text-left p-3">Цена/л</th>
                            <th class="text-left p-3">Обща сума</th>
                            <th class="text-left p-3">Бележки</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($collections as $collection)

                            <tr class="border-b">

                                <td class="p-3">
                                    {{ $collection->collection_date }}
                                </td>

                                <td class="p-3">
                                    {{ $collection->liters }} L
                                </td>

                                <td class="p-3">
                                    {{ number_format($collection->price_per_liter, 2) }} лв
                                </td>

                                <td class="p-3 font-bold">
                                    {{ number_format($collection->total_price, 2) }} лв
                                </td>

                                <td class="p-3">
                                    {{ $collection->notes }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5" class="text-center p-6 text-gray-500">
                                    Все още няма записани събирания.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

</x-app-layout>