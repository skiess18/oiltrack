<x-app-layout>

    <x-slot name="header">

        <div class="flex justify-between items-center">

            <h2 class="text-3xl font-bold">
                ✏️ Редакция на обект
            </h2>

            <a href="{{ route('clients.index') }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-3 rounded-xl">

                ← Назад

            </a>

        </div>

    </x-slot>

    <div class="max-w-3xl mx-auto">

        <div class="bg-white rounded-3xl shadow-xl p-8">

            <form action="{{ route('clients.update', $client) }}" method="POST">

                @csrf
                @method('PUT')

                <div class="space-y-5">

                    <div>
                        <label class="block font-semibold mb-2">🏢 Име</label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $client->name) }}"
                            class="w-full border rounded-xl p-3"
                            required>
                    </div>

                    <div>
                        <label class="block font-semibold mb-2">📍 Адрес</label>
                        <input
                            type="text"
                            name="address"
                            value="{{ old('address', $client->address) }}"
                            class="w-full border rounded-xl p-3"
                            required>
                    </div>

                    <div>
                        <label class="block font-semibold mb-2">📞 Телефон</label>
                        <input
                            type="text"
                            name="phone"
                            value="{{ old('phone', $client->phone) }}"
                            class="w-full border rounded-xl p-3">
                    </div>

                    <div>
                        <label class="block font-semibold mb-2">🛢️ Капацитет (литра)</label>
                        <input
                            type="number"
                            name="capacity"
                            value="{{ old('capacity', $client->capacity) }}"
                            class="w-full border rounded-xl p-3">
                    </div>

                    <div>
                        <label class="block font-semibold mb-2">🌍 Географска ширина</label>
                        <input
                            type="text"
                            name="latitude"
                            value="{{ old('latitude', $client->latitude) }}"
                            class="w-full border rounded-xl p-3">
                    </div>

                    <div>
                        <label class="block font-semibold mb-2">🌍 Географска дължина</label>
                        <input
                            type="text"
                            name="longitude"
                            value="{{ old('longitude', $client->longitude) }}"
                            class="w-full border rounded-xl p-3">
                    </div>

                    <button
                        type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-xl font-bold">

                        💾 Запази промените

                    </button>

                </div>

            </form>

        </div>

    </div>

</x-app-layout>