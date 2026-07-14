<x-app-layout>

    <x-slot name="header">

        <div class="flex justify-between items-center">

            <div>

                <h2 class="text-3xl font-bold">
                    🛢️ История на събиранията
                </h2>

                <p class="text-gray-500 mt-1">
                    {{ $client->name }}
                </p>

            </div>

            <a
                href="{{ route('collections.create',$client) }}"
                class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl">

                ➕ Ново събиране

            </a>

        </div>

    </x-slot>

    <div class="max-w-7xl mx-auto">

        @if(session('success'))

            <div class="mb-6 bg-green-100 border border-green-300 text-green-700 rounded-xl p-4">

                {{ session('success') }}

            </div>

        @endif

        <div class="bg-white rounded-3xl shadow-xl overflow-hidden">

            <table class="w-full">

                <thead class="bg-slate-100">

                    <tr>

                        <th class="text-left p-4">📅 Дата</th>

                        <th class="text-left p-4">🛢️ Литри</th>

                        <th class="text-left p-4">💰 Цена/л</th>

                        <th class="text-left p-4">💵 Обща сума</th>

                        <th class="text-left p-4">📝 Бележки</th>

                        <th class="text-center p-4">⚙️ Действия</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($collections as $collection)

                        <tr class="border-t hover:bg-slate-50">

                            <td class="p-4">

                                {{ \Carbon\Carbon::parse($collection->collection_date)->format('d.m.Y') }}

                            </td>

                            <td class="p-4 font-semibold">

                                {{ number_format($collection->liters,2) }} L

                            </td>

                            <td class="p-4">

                                {{ number_format($collection->price_per_liter,2) }} лв.

                            </td>

                            <td class="p-4 font-bold text-green-600">

                                {{ number_format($collection->total_price,2) }} лв.

                            </td>

                            <td class="p-4">

                                {{ $collection->notes ?: '-' }}

                            </td>

                            <td class="p-4">

                                <div class="flex justify-center gap-2">

                                    <a
                                        href="{{ route('collections.show',$collection) }}"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">

                                        👁️

                                    </a>

                                    <a
                                        href="{{ route('collections.pdf',$collection) }}"
                                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">

                                        📄

                                    </a>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="text-center py-10 text-gray-500">

                                Все още няма записани събирания.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</x-app-layout>