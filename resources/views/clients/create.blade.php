<x-app-layout>

    <x-slot name="header">
        <div class="flex justify-between items-center w-full">

            <div>
                <h2 class="text-3xl font-bold text-slate-800">
                    ➕ Нов обект
                </h2>

                <p class="text-gray-500">
                    Добавяне на нов клиент
                </p>
            </div>

        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto">

        @if ($errors->any())

            <div class="bg-red-100 border border-red-300 text-red-700 rounded-xl p-5 mb-6">

                <ul class="list-disc ml-6">

                    @foreach ($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif

        <form action="{{ route('clients.store') }}" method="POST">

            @csrf

            <div class="grid md:grid-cols-2 gap-8">

                <!-- Лява колона -->

                <div class="bg-white rounded-2xl shadow-lg p-8">

                    <h3 class="text-xl font-bold mb-6">
                        🏢 Данни за обекта
                    </h3>

                    <div class="mb-5">

                        <label class="font-semibold block mb-2">
                            Име
                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500"
                            required>

                    </div>

                    <div class="mb-5 relative">

                        <label class="font-semibold block mb-2">
                            📍 Адрес
                        </label>

                        <input
                            id="address"
                            type="text"
                            name="address"
                            value="{{ old('address') }}"
                            class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500"
                            placeholder="Започни да пишеш адрес..."
                            autocomplete="off"
                            required>

                        <div
                            id="address-results"
                            class="absolute left-0 right-0 mt-1 bg-white border rounded-xl shadow-xl hidden z-50 max-h-72 overflow-y-auto">

                        </div>

                    </div>

                    <div class="mb-5">

                        <button
                            type="button"
                            id="current-location"
                            class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-xl">

                            📍 Използвай текущото местоположение

                        </button>

                    </div>

                    <div class="mb-5">

                        <label class="font-semibold block mb-2">
                            Телефон
                        </label>

                        <input
                            type="text"
                            name="phone"
                            value="{{ old('phone') }}"
                            class="w-full border rounded-xl px-4 py-3">

                    </div>

                    <div class="mb-5">

                        <label class="font-semibold block mb-2">
                            Лице за контакт
                        </label>

                        <input
                            type="text"
                            name="contact_person"
                            value="{{ old('contact_person') }}"
                            class="w-full border rounded-xl px-4 py-3">

                    </div>

                </div>

                <!-- Дясна колона -->

                <div class="bg-white rounded-2xl shadow-lg p-8">

                    <h3 class="text-xl font-bold mb-6">
                        🛢 Допълнителна информация
                    </h3>

                    <div class="mb-5">

                        <label class="font-semibold block mb-2">
                            Капацитет (литри)
                        </label>

                        <input
                            type="number"
                            name="capacity"
                            value="{{ old('capacity',0) }}"
                            class="w-full border rounded-xl px-4 py-3">

                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-5">

                        <div>

                            <label class="font-semibold block mb-2">
                                GPS Latitude
                            </label>

                            <input
                                id="latitude"
                                type="text"
                                name="latitude"
                                value="{{ old('latitude') }}"
                                class="w-full border rounded-xl px-4 py-3 bg-gray-100"
                                readonly>

                        </div>

                        <div>

                            <label class="font-semibold block mb-2">
                                GPS Longitude
                            </label>

                            <input
                                id="longitude"
                                type="text"
                                name="longitude"
                                value="{{ old('longitude') }}"
                                class="w-full border rounded-xl px-4 py-3 bg-gray-100"
                                readonly>

                        </div>

                    </div>

                    <div class="mb-6">

                        <label class="font-semibold block mb-2">
                            Бележки
                        </label>

                        <textarea
                            name="notes"
                            rows="6"
                            class="w-full border rounded-xl px-4 py-3">{{ old('notes') }}</textarea>

                    </div>

                </div>

            </div>

            <div class="mt-8 flex gap-4">

                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-xl font-semibold shadow">

                    💾 Запази обекта

                </button>

                <a
                    href="{{ route('clients.index') }}"
                    class="bg-gray-300 hover:bg-gray-400 px-8 py-4 rounded-xl">

                    Отказ

                </a>

            </div>

        </form>

    </div>
    <script>

const addressInput = document.getElementById('address');
const resultsBox = document.getElementById('address-results');
const latitudeInput = document.getElementById('latitude');
const longitudeInput = document.getElementById('longitude');
const locationButton = document.getElementById('current-location');

let timeout = null;

/*
|--------------------------------------------------------------------------
| Автоматично търсене на адрес
|--------------------------------------------------------------------------
*/

addressInput.addEventListener('input', function () {

    clearTimeout(timeout);

    const query = this.value.trim();

    if (query.length < 3) {

        resultsBox.innerHTML = '';
        resultsBox.classList.add('hidden');

        return;

    }

    timeout = setTimeout(async () => {

        try {

            const response = await fetch(
                `https://nominatim.openstreetmap.org/search?format=json&countrycodes=bg&limit=5&q=${encodeURIComponent(query)}`
            );

            const data = await response.json();

            resultsBox.innerHTML = '';

            if (!data.length) {

                resultsBox.classList.add('hidden');
                return;

            }

            data.forEach(place => {

                const item = document.createElement('div');

                item.className =
                    "px-4 py-3 border-b hover:bg-slate-100 cursor-pointer";

                item.innerHTML = `
                    <div class="font-semibold">
                        📍 ${place.display_name}
                    </div>
                `;

                item.onclick = () => {

                    addressInput.value = place.display_name;

                    latitudeInput.value = place.lat;
                    longitudeInput.value = place.lon;

                    resultsBox.classList.add('hidden');

                };

                resultsBox.appendChild(item);

            });

            resultsBox.classList.remove('hidden');

        } catch (error) {

            console.error(error);

        }

    }, 400);

});

/*
|--------------------------------------------------------------------------
| Скриване на предложенията
|--------------------------------------------------------------------------
*/

document.addEventListener('click', function (e) {

    if (
        !resultsBox.contains(e.target) &&
        e.target !== addressInput
    ) {

        resultsBox.classList.add('hidden');

    }

});

/*
|--------------------------------------------------------------------------
| Текущо местоположение
|--------------------------------------------------------------------------
*/

locationButton.addEventListener('click', function () {

    if (!navigator.geolocation) {

        alert('Вашият браузър не поддържа GPS.');

        return;

    }

    locationButton.disabled = true;

    locationButton.innerHTML = "⏳ Зареждане...";

    navigator.geolocation.getCurrentPosition(

        async function(position){

            const lat = position.coords.latitude;
            const lon = position.coords.longitude;

            latitudeInput.value = lat;
            longitudeInput.value = lon;

            try{

                const response = await fetch(
                    `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`
                );

                const data = await response.json();

                if(data.display_name){

                    addressInput.value = data.display_name;

                }

            }catch(error){

                console.error(error);

            }

            locationButton.disabled = false;

            locationButton.innerHTML =
                "📍 Използвай текущото местоположение";

        },

        function(){

            alert("Не можа да бъде получено местоположението.");

            locationButton.disabled = false;

            locationButton.innerHTML =
                "📍 Използвай текущото местоположение";

        }

    );

});

</script>

</x-app-layout>