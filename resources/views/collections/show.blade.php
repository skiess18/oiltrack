<x-app-layout>

    <x-slot name="header">

        <div class="flex justify-between items-center">

            <div>

                <h2 class="text-3xl font-bold">
                    📋 Детайли за събирането
                </h2>

                <p class="text-gray-500">
                    {{ $collection->client->name }}
                </p>

            </div>

            <div class="flex gap-3">

                <a href="{{ route('collections.pdf',$collection) }}"
                   class="bg-red-600 hover:bg-red-700 text-white px-5 py-3 rounded-xl">

                    📄 PDF

                </a>

                <a href="{{ route('collections.index',$collection->client) }}"
                   class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-3 rounded-xl">

                    ← Назад

                </a>

            </div>

        </div>

    </x-slot>

    <div class="max-w-5xl mx-auto space-y-6">

        <div class="bg-white rounded-3xl shadow-xl p-8">

            <h3 class="text-2xl font-bold mb-6">

                🏢 Информация

            </h3>

            <div class="grid md:grid-cols-2 gap-6">

                <div>

                    <div class="text-gray-500">
                        Клиент
                    </div>

                    <div class="font-bold text-xl">
                        {{ $collection->client->name }}
                    </div>

                </div>

                <div>

                    <div class="text-gray-500">
                        Адрес
                    </div>

                    <div class="font-bold">
                        {{ $collection->client->address }}
                    </div>

                </div>

                <div>

                    <div class="text-gray-500">
                        Дата
                    </div>

                    <div class="font-bold">

                        {{ \Carbon\Carbon::parse($collection->collection_date)->format('d.m.Y') }}

                    </div>

                </div>

                <div>

                    <div class="text-gray-500">
                        Литри
                    </div>

                    <div class="font-bold text-xl text-green-600">

                        {{ number_format($collection->liters,2) }} L

                    </div>

                </div>

                <div>

                    <div class="text-gray-500">
                        Цена / литър
                    </div>

                    <div class="font-bold">

                        {{ number_format($collection->price_per_liter,2) }} лв.

                    </div>

                </div>

                <div>

                    <div class="text-gray-500">
                        Обща сума
                    </div>

                    <div class="font-bold text-2xl text-blue-600">

                        {{ number_format($collection->total_price,2) }} лв.

                    </div>

                </div>

            </div>

        </div>

        <div class="bg-white rounded-3xl shadow-xl p-8">

            <h3 class="text-2xl font-bold mb-6">

                📍 GPS

            </h3>

            @if($collection->latitude)

                <p class="mb-5">

                    {{ $collection->latitude }},
                    {{ $collection->longitude }}

                </p>

                <a
                    href="https://www.google.com/maps?q={{ $collection->latitude }},{{ $collection->longitude }}"
                    target="_blank"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl">

                    🗺️ Отвори в Google Maps

                </a>

            @else

                <p class="text-gray-500">

                    Няма записани GPS координати.

                </p>

            @endif

        </div>

        @if($collection->signature)

            <div class="bg-white rounded-3xl shadow-xl p-8">

                <h3 class="text-2xl font-bold mb-6">

                    ✍️ Подпис

                </h3>

                <img
                    src="{{ asset('storage/'.$collection->signature) }}"
                    class="border rounded-xl max-w-full">

            </div>

        @endif

        @if($collection->notes)

            <div class="bg-white rounded-3xl shadow-xl p-8">

                <h3 class="text-2xl font-bold mb-4">

                    📝 Бележки

                </h3>

                {{ $collection->notes }}

            </div>

        @endif

    </div>

</x-app-layout>