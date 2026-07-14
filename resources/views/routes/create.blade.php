<x-app-layout>
   
<x-slot name="header">

<div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">

    <div>

        <h2 class="text-3xl font-bold text-slate-800">

            🚛 Нов маршрут

        </h2>

        <p class="text-gray-500">

            Създаване на маршрут за събиране на използвано масло

        </p>

    </div>

    <a
        href="{{ route('routes.index') }}"
        class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-xl">

        ← Назад

    </a>

</div>

</x-slot>

<div class="max-w-7xl mx-auto space-y-6">

@if ($errors->any())

<div class="bg-red-100 border border-red-300 text-red-700 rounded-2xl p-5">

<ul class="list-disc ml-6">

@foreach ($errors->all() as $error)

<li>{{ $error }}</li>

@endforeach

</ul>

</div>

@endif

<form
action="{{ route('routes.store') }}"
method="POST">

@csrf

<div class="grid lg:grid-cols-3 gap-6">

<div class="lg:col-span-1">

<div class="bg-white rounded-2xl shadow-lg p-6">

<h3 class="text-xl font-bold mb-6">

⚙️ Данни за маршрута

</h3>

<div class="mb-5">

<label class="block font-semibold mb-2">

📅 Дата

</label>

<input
type="date"
name="route_date"
value="{{ old('route_date',date('Y-m-d')) }}"
class="w-full border rounded-xl px-4 py-3"
required>

</div>

<div class="mb-5">

<label class="block font-semibold mb-2">

👤 Шофьор

</label>

<select
name="driver_id"
class="w-full border rounded-xl px-4 py-3"
required>

<option value="">

Избери шофьор

</option>

@foreach($drivers as $driver)

<option
value="{{ $driver->id }}"
{{ old('driver_id')==$driver->id ? 'selected' : '' }}>

{{ $driver->name }}

</option>

@endforeach

</select>

</div>

<div class="mb-5">

<label class="block font-semibold mb-2">

🚚 Автомобил

</label>

<select
name="vehicle_id"
class="w-full border rounded-xl px-4 py-3"
required>

<option value="">

Избери автомобил

</option>

@foreach($vehicles as $vehicle)

<option
value="{{ $vehicle->id }}"
{{ old('vehicle_id')==$vehicle->id ? 'selected' : '' }}>

{{ $vehicle->registration }}
-
{{ $vehicle->brand }}
{{ $vehicle->model }}

</option>

@endforeach

</select>

</div>

<div class="mb-5">

<label class="block font-semibold mb-2">

📋 Статус

</label>

<select
name="status"
class="w-full border rounded-xl px-4 py-3">

<option value="planned">

Планиран

</option>

<option value="in_progress">

В процес

</option>

<option value="completed">

Завършен

</option>

<option value="cancelled">

Отменен

</option>

</select>

</div>

<div class="mb-5">

<label class="block font-semibold mb-2">

📝 Бележки

</label>

<textarea
name="notes"
rows="6"
class="w-full border rounded-xl px-4 py-3">{{ old('notes') }}</textarea>

</div>

<button
type="submit"
class="w-full bg-blue-600 hover:bg-blue-700 text-white rounded-xl py-4 text-lg font-bold">

💾 Запази маршрута

</button>

</div>

</div>
<div class="lg:col-span-2">

<div class="bg-white rounded-2xl shadow-lg p-6">

<div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4 mb-6">

<h3 class="text-xl font-bold">

🏢 Обекти за посещение

</h3>

<div class="flex flex-wrap gap-2">

<button
type="button"
id="selectAll"
class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl">

✔️ Всички

</button>

<button
type="button"
id="clearAll"
class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl">

❌ Изчисти

</button>

<button
type="button"
id="autoSelect"
class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-xl">

🤖 Само нуждаещите се

</button>

</div>

</div>

<input
type="text"
id="searchClient"
placeholder="🔍 Търси обект..."
class="w-full border rounded-xl px-4 py-3 mb-6">

<div
id="clientsList"
class="border rounded-2xl overflow-hidden max-h-[650px] overflow-y-auto">

@forelse($clients as $client)

<label
class="client-item flex justify-between items-start gap-4 p-5 border-b hover:bg-slate-50 cursor-pointer">

<div class="flex-1">

<div class="flex items-center gap-3 flex-wrap">

<h4 class="font-bold text-lg">

🏢 {{ $client->name }}

</h4>

@if($client->needs_collection)

<span
class="bg-red-100 text-red-700 text-xs font-bold px-2 py-1 rounded-full">

🔴 За посещение

</span>

@else

<span
class="bg-green-100 text-green-700 text-xs font-bold px-2 py-1 rounded-full">

🟢 Добре

</span>

@endif

</div>

<div class="text-gray-500 mt-2">

📍 {{ $client->address }}

</div>

<div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mt-4">

<div>

<div class="text-xs text-gray-500">

Средно

</div>

<div class="font-semibold">

{{ number_format($client->average_liters,1) }} L

</div>

</div>

<div>

<div class="text-xs text-gray-500">

Капацитет

</div>

<div class="font-semibold">

{{ $client->capacity }} L

</div>

</div>

<div>

<div class="text-xs text-gray-500">

Последно събиране

</div>

<div class="font-semibold">

@if($client->last_collection)

{{ \Carbon\Carbon::parse($client->last_collection->collection_date)->format('d.m.Y') }}

@else

—

@endif

</div>

</div>

<div>

<div class="text-xs text-gray-500">

Дни

</div>

<div class="font-bold">

{{ $client->days_since_last_collection ?? '-' }}

</div>

</div>

</div>

@if($client->phone)

<div class="mt-4">

<a
href="tel:{{ $client->phone }}"
class="text-blue-600 hover:underline">

📞 {{ $client->phone }}

</a>

</div>

@endif

</div>

<div class="flex items-center">

<input
type="checkbox"
name="clients[]"
value="{{ $client->id }}"
class="client-checkbox w-6 h-6 rounded">

</div>

</label>

@empty

<div class="text-center py-10 text-gray-500">

Няма добавени обекти.

</div>

@endforelse

</div>
<div class="mt-6 flex flex-col lg:flex-row gap-4">

    <button
        type="button"
        id="saveRoute"
        class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-xl font-semibold">

        🤖 Избери обектите за посещение

    </button>

    <button
        type="submit"
        class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-semibold">

        💾 Запази маршрута

    </button>

</div>

</div>

</div>

</div>

</form>

</div>

<script>

const search = document.getElementById('searchClient');
const cards = document.querySelectorAll('.client-item');
const checkboxes = document.querySelectorAll('.client-checkbox');

search.addEventListener('keyup', function () {

    let value = this.value.toLowerCase();

    cards.forEach(card => {

        if (card.innerText.toLowerCase().includes(value)) {

            card.style.display = 'flex';

        } else {

            card.style.display = 'none';

        }

    });

});

document.getElementById('selectAll').addEventListener('click', function () {

    checkboxes.forEach(c => c.checked = true);

});

document.getElementById('clearAll').addEventListener('click', function () {

    checkboxes.forEach(c => c.checked = false);

});

document.getElementById('autoSelect').addEventListener('click', function () {

    cards.forEach(card => {

        const badge = card.querySelector('span');
        const checkbox = card.querySelector('.client-checkbox');

        if (badge && badge.innerText.includes('За посещение')) {

            checkbox.checked = true;

        }

    });

});

</script>

</x-app-layout>