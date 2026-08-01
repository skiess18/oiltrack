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
                        <label class="block font-semibold mb-2">Име на фирма</label>
                        <input type="text" name="company_name" value="{{ old('company_name', $client->company_name) }}" class="w-full border rounded-xl p-3" required>
                    </div>

                    <div>
                        <label class="block font-semibold mb-2">Булстат</label>
                        <input type="text" name="bulstat" value="{{ old('bulstat', $client->bulstat) }}" maxlength="20" class="w-full border rounded-xl p-3" required>
                    </div>

                    <div>
                        <label class="block font-semibold mb-2">Начин на плащане</label>
                        <div class="flex gap-5">
                            <label class="inline-flex items-center gap-2">
                                <input type="radio" name="payment_method" value="cash" {{ old('payment_method', $client->payment_method) === 'cash' ? 'checked' : '' }} required>
                                В брой
                            </label>
                            <label class="inline-flex items-center gap-2">
                                <input type="radio" name="payment_method" value="bank_transfer" {{ old('payment_method', $client->payment_method) === 'bank_transfer' ? 'checked' : '' }}>
                                Банков превод
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block font-semibold mb-2">Цена за литър</label>
                            <input type="number" name="price_per_liter" value="{{ old('price_per_liter', $client->price_per_liter) }}" min="0" step="0.01" class="w-full border rounded-xl p-3" required>
                        </div>
                        <div>
                            <label class="block font-semibold mb-2">Интервал за посещение (дни)</label>
                            <input type="number" name="visit_interval_days" value="{{ old('visit_interval_days', $client->visit_interval_days) }}" min="1" step="1" class="w-full border rounded-xl p-3" required>
                        </div>
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
                            class="w-full border rounded-xl p-3" required>
                    </div>

                    <div>
                        <label class="block font-semibold mb-2">Представител</label>
                        <input type="text" name="representative" value="{{ old('representative', $client->representative) }}" class="w-full border rounded-xl p-3" required>
                    </div>

                    <div>
                        <label class="block font-semibold mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', $client->email) }}" class="w-full border rounded-xl p-3" required>
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
