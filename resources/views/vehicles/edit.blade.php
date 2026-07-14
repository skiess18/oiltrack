<x-app-layout>

    <x-slot name="header">

        <div class="flex justify-between items-center">

            <div>

                <h2 class="text-3xl font-bold">
                    ✏️ Редакция на автомобил
                </h2>

                <p class="text-gray-500">

                    {{ $vehicle->brand }} {{ $vehicle->model }}

                </p>

            </div>

            <a
                href="{{ route('vehicles.index') }}"
                class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-xl">

                ← Назад

            </a>

        </div>

    </x-slot>

    <div class="max-w-5xl mx-auto">

        @if ($errors->any())

            <div class="bg-red-100 border border-red-300 text-red-700 rounded-xl p-4 mb-6">

                <ul class="list-disc ml-5">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif

        <form
            action="{{ route('vehicles.update',$vehicle) }}"
            method="POST"
            enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="bg-white rounded-3xl shadow-xl p-8">

                <div class="grid md:grid-cols-2 gap-6">

                    <div>

                        <label class="block mb-2 font-semibold">

                            Марка

                        </label>

                        <input
                            type="text"
                            name="brand"
                            value="{{ old('brand',$vehicle->brand) }}"
                            class="w-full border rounded-xl p-3"
                            required>

                    </div>

                    <div>

                        <label class="block mb-2 font-semibold">

                            Модел

                        </label>

                        <input
                            type="text"
                            name="model"
                            value="{{ old('model',$vehicle->model) }}"
                            class="w-full border rounded-xl p-3"
                            required>

                    </div>

                    <div>

                        <label class="block mb-2 font-semibold">

                            Година

                        </label>

                        <input
                            type="number"
                            name="year"
                            value="{{ old('year',$vehicle->year) }}"
                            class="w-full border rounded-xl p-3">

                    </div>

                    <div>

                        <label class="block mb-2 font-semibold">

                            Регистрационен номер

                        </label>

                        <input
                            type="text"
                            name="registration"
                            value="{{ old('registration',$vehicle->registration) }}"
                            class="w-full border rounded-xl p-3"
                            required>

                    </div>

                    <div>

                        <label class="block mb-2 font-semibold">

                            VIN

                        </label>

                        <input
                            type="text"
                            name="vin"
                            value="{{ old('vin',$vehicle->vin) }}"
                            class="w-full border rounded-xl p-3">

                    </div>

                    <div>

                        <label class="block mb-2 font-semibold">

                            Цвят

                        </label>

                        <input
                            type="text"
                            name="color"
                            value="{{ old('color',$vehicle->color) }}"
                            class="w-full border rounded-xl p-3">

                    </div>

                    <div>

                        <label class="block mb-2 font-semibold">

                            Шофьор

                        </label>

                        <input
                            type="text"
                            name="driver"
                            value="{{ old('driver',$vehicle->driver) }}"
                            class="w-full border rounded-xl p-3">

                    </div>

                    <div>

                        <label class="block mb-2 font-semibold">

                            Разход

                        </label>

                        <input
                            type="number"
                            step="0.1"
                            name="fuel_consumption"
                            value="{{ old('fuel_consumption',$vehicle->fuel_consumption) }}"
                            class="w-full border rounded-xl p-3">

                    </div>

                    <div>

                        <label class="block mb-2 font-semibold">

                            Километри

                        </label>

                        <input
                            type="number"
                            name="current_km"
                            value="{{ old('current_km',$vehicle->current_km) }}"
                            class="w-full border rounded-xl p-3">

                    </div>

                    <div>

                        <label class="block mb-2 font-semibold">

                            Последен сервиз

                        </label>

                        <input
                            type="date"
                            name="last_service"
                            value="{{ old('last_service',optional($vehicle->last_service)->format('Y-m-d')) }}"
                            class="w-full border rounded-xl p-3">

                    </div>

                    <div>

                        <label class="block mb-2 font-semibold">

                            Следващ сервиз

                        </label>

                        <input
                            type="number"
                            name="next_service_km"
                            value="{{ old('next_service_km',$vehicle->next_service_km) }}"
                            class="w-full border rounded-xl p-3">

                    </div>

                    <div>

                        <label class="block mb-2 font-semibold">

                            ГТП

                        </label>

                        <input
                            type="date"
                            name="inspection_date"
                            value="{{ old('inspection_date',optional($vehicle->inspection_date)->format('Y-m-d')) }}"
                            class="w-full border rounded-xl p-3">

                    </div>

                    <div>

                        <label class="block mb-2 font-semibold">

                            Гражданска

                        </label>

                        <input
                            type="date"
                            name="insurance_date"
                            value="{{ old('insurance_date',optional($vehicle->insurance_date)->format('Y-m-d')) }}"
                            class="w-full border rounded-xl p-3">

                    </div>

                    <div>

                        <label class="block mb-2 font-semibold">

                            Статус

                        </label>

                        <select
                            name="status"
                            class="w-full border rounded-xl p-3">

                            <option value="active" {{ $vehicle->status=='active'?'selected':'' }}>Активен</option>

                            <option value="service" {{ $vehicle->status=='service'?'selected':'' }}>В сервиз</option>

                            <option value="inactive" {{ $vehicle->status=='inactive'?'selected':'' }}>Неактивен</option>

                        </select>

                    </div>

                    <div>

                        <label class="block mb-2 font-semibold">

                            Смени снимката

                        </label>

                        <input
                            type="file"
                            name="photo"
                            class="w-full">

                    </div>

                </div>

                <div class="mt-6">

                    <label class="block mb-2 font-semibold">

                        Бележки

                    </label>

                    <textarea
                        name="notes"
                        rows="5"
                        class="w-full border rounded-xl p-3">{{ old('notes',$vehicle->notes) }}</textarea>

                </div>

                <div class="mt-8">

                    <button
                        class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-xl font-bold">

                        💾 Запази промените

                    </button>

                </div>

            </div>

        </form>

    </div>

</x-app-layout>