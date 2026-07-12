<x-app-layout>

<x-slot name="header">

<div class="flex justify-between items-center">

    <div>

        <h2 class="text-3xl font-bold">

            🚛 Активен маршрут

        </h2>

        <p class="text-gray-500">

            {{ \Carbon\Carbon::parse($route->route_date)->format('d.m.Y') }}

        </p>

    </div>

    <a
        href="{{ route('routes.show',$route) }}"
        class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-xl">

        ← Назад

    </a>

</div>

</x-slot>

<div class="max-w-6xl mx-auto space-y-6">

@if(session('success'))

<div class="bg-green-100 border border-green-300 rounded-xl p-4 text-green-700">

    {{ session('success') }}

</div>

@endif

<div id="gpsStatus" class="hidden"></div>

<div class="grid lg:grid-cols-3 gap-6">

<div class="lg:col-span-2">

<div class="bg-white rounded-3xl shadow-xl p-8">

<div class="text-center">

<div class="text-7xl">

🏢

</div>

<h1 class="text-4xl font-bold mt-4">

{{ $client->name }}

</h1>

<p class="text-gray-500 mt-3">

{{ $client->address }}

</p>

</div>

<div class="grid grid-cols-2 gap-4 mt-8">

<div class="bg-slate-100 rounded-xl p-5 text-center">

<div class="text-gray-500">

📞 Телефон

</div>

<div class="font-bold text-xl mt-2">

{{ $client->phone ?: '-' }}

</div>

</div>

<div class="bg-slate-100 rounded-xl p-5 text-center">

<div class="text-gray-500">

🛢️ Капацитет

</div>

<div class="font-bold text-xl mt-2">

{{ $client->capacity }} L

</div>

</div>

</div>

<div class="grid grid-cols-2 gap-4 mt-8">
    @if($client->latitude && $client->longitude)

<a
    href="https://www.google.com/maps/dir/?api=1&destination={{ $client->latitude }},{{ $client->longitude }}"
    target="_blank"
    class="bg-blue-600 hover:bg-blue-700 text-white rounded-xl p-6 text-center">

    <div class="text-5xl">🧭</div>

    <div class="mt-3 font-bold">
        Навигация
    </div>

</a>

@else

<a
    href="https://www.google.com/maps/search/?api=1&query={{ urlencode($client->address) }}"
    target="_blank"
    class="bg-blue-600 hover:bg-blue-700 text-white rounded-xl p-6 text-center">

    <div class="text-5xl">🗺️</div>

    <div class="mt-3 font-bold">
        Карта
    </div>

</a>

@endif

@if($client->phone)

<a
    href="tel:{{ $client->phone }}"
    class="bg-green-600 hover:bg-green-700 text-white rounded-xl p-6 text-center">

    <div class="text-5xl">📞</div>

    <div class="mt-3 font-bold">
        Обади се
    </div>

</a>

@else

<div class="bg-gray-200 rounded-xl p-6 text-center">

    <div class="text-5xl">📞</div>

    <div class="mt-3 text-gray-500 font-bold">
        Няма телефон
    </div>

</div>

@endif

<a
    href="{{ route('collections.create',$client) }}?route_id={{ $route->id }}"
    class="bg-orange-500 hover:bg-orange-600 text-white rounded-xl p-6 text-center">

    <div class="text-5xl">🛢️</div>

    <div class="mt-3 font-bold">
        Ново събиране
    </div>

</a>

<button
    id="arriveButton"
    type="button"
    class="bg-purple-600 hover:bg-purple-700 text-white rounded-xl p-6">

    <div class="text-5xl">📍</div>

    <div class="mt-3 font-bold">
        Пристигнах
    </div>

</button>

</div>

<hr class="my-8">

<form
    id="collectionForm"
    action="{{ route('collections.store',$client) }}"
    method="POST">

    @csrf

    <input
        type="hidden"
        name="route_id"
        value="{{ $route->id }}">

    <input
        type="hidden"
        id="latitude"
        name="latitude">

    <input
        type="hidden"
        id="longitude"
        name="longitude">
        <div class="grid md:grid-cols-2 gap-5">

    <div>

        <label class="block mb-2 font-semibold">

            📅 Дата

        </label>

        <input
            type="date"
            name="collection_date"
            value="{{ date('Y-m-d') }}"
            class="w-full border rounded-xl p-3"
            required>

    </div>

    <div>

        <label class="block mb-2 font-semibold">

            🛢️ Литри

        </label>

        <input
            id="liters"
            type="number"
            step="0.01"
            min="0"
            name="liters"
            class="w-full border rounded-xl p-3"
            required>

    </div>

    <div>

        <label class="block mb-2 font-semibold">

            💰 Цена за литър

        </label>

        <input
            id="price"
            type="number"
            step="0.01"
            min="0"
            value="0"
            name="price_per_liter"
            class="w-full border rounded-xl p-3"
            required>

    </div>

    <div>

        <label class="block mb-2 font-semibold">

            💵 Обща сума

        </label>

        <input
            id="totalPrice"
            type="text"
            class="w-full bg-slate-100 rounded-xl p-3 font-bold"
            value="0.00 лв."
            readonly>

    </div>

</div>

<div class="mt-6">

    <label class="block mb-2 font-semibold">

        📝 Бележка

    </label>

    <textarea
        name="notes"
        rows="4"
        class="w-full border rounded-xl p-3"
        placeholder="Допълнителна информация..."></textarea>

</div>

<div class="mt-8">

    <label class="block mb-3 font-semibold text-lg">

        ✍️ Подпис на клиента

    </label>

    <canvas
        id="signature-pad"
        class="w-full h-56 border-2 border-dashed border-slate-300 rounded-xl bg-white">

    </canvas>

    <input
        type="hidden"
        id="signature"
        name="signature">

</div>

<div class="flex gap-4 mt-6">

    <button
        type="button"
        id="clearSignature"
        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white rounded-xl py-3">

        🗑️ Изчисти

    </button>

    <button
        type="button"
        id="saveButton"
        class="flex-1 bg-green-600 hover:bg-green-700 text-white rounded-xl py-3 font-bold">

        💾 Запази събирането

    </button>

</div>

</form>

</div>

</div>

<div class="space-y-6">

@php

$visited = $route->clients->where('pivot.visited',true)->count();
$total = $route->clients->count();
$remaining = $total - $visited;
$percent = $total ? round(($visited/$total)*100) : 0;

@endphp
<div class="bg-white rounded-3xl shadow-xl p-6">

    <h3 class="text-2xl font-bold mb-6">

        📊 Прогрес

    </h3>

    <div class="text-center">

        <div class="text-5xl font-bold text-blue-600">

            {{ $visited }} / {{ $total }}

        </div>

        <div class="text-gray-500 mt-2">

            Посетени обекти

        </div>

    </div>

    <div class="mt-6 w-full h-5 bg-slate-200 rounded-full overflow-hidden">

        <div
            class="h-5 bg-green-500 transition-all duration-500"
            style="width: {{ $percent }}%">

        </div>

    </div>

    <div class="text-center mt-4 font-bold">

        {{ $percent }}%

    </div>

</div>

<div class="bg-white rounded-3xl shadow-xl p-6">

    <h3 class="text-2xl font-bold mb-5">

        🚛 Информация

    </h3>

    <div class="space-y-4">

        <div class="flex justify-between">
            <span>Шофьор</span>
            <strong>{{ $route->driver ?: '-' }}</strong>
        </div>

        <div class="flex justify-between">
            <span>Дата</span>
            <strong>{{ \Carbon\Carbon::parse($route->route_date)->format('d.m.Y') }}</strong>
        </div>

        <div class="flex justify-between">
            <span>Общо обекти</span>
            <strong>{{ $total }}</strong>
        </div>

        <div class="flex justify-between">
            <span>Остават</span>
            <strong>{{ $remaining }}</strong>
        </div>

        <div class="flex justify-between">
            <span>Статус</span>

            <strong>

                @switch($route->status)

                    @case('planned')
                        🟡 Планиран
                        @break

                    @case('in_progress')
                        🔵 В процес
                        @break

                    @case('completed')
                        🟢 Завършен
                        @break

                    @default
                        🔴 Отменен

                @endswitch

            </strong>

        </div>

    </div>

</div>

</div>

</div>

<script>

const liters=document.getElementById('liters');
const price=document.getElementById('price');
const totalPrice=document.getElementById('totalPrice');

function calc(){

    totalPrice.value=((parseFloat(liters.value)||0)*(parseFloat(price.value)||0)).toFixed(2)+' лв.';

}

liters.addEventListener('input',calc);
price.addEventListener('input',calc);

calc();

document.getElementById('arriveButton').onclick=function(){

    if(!navigator.geolocation){

        alert('GPS не се поддържа.');

        return;

    }

    navigator.geolocation.getCurrentPosition(function(position){

        document.getElementById('latitude').value=position.coords.latitude;
        document.getElementById('longitude').value=position.coords.longitude;

        document.getElementById('gpsStatus').className='bg-green-100 border border-green-300 text-green-700 rounded-xl p-4';

        document.getElementById('gpsStatus').innerHTML='✅ GPS е записан успешно.';

    });

};

document.getElementById('saveButton').onclick=function(){

    document.getElementById('collectionForm').submit();

};
const canvas = document.getElementById('signature-pad');
const ctx = canvas.getContext('2d');

canvas.width = canvas.offsetWidth;
canvas.height = 220;

ctx.lineWidth = 2;
ctx.lineCap = 'round';
ctx.strokeStyle = '#000';

let drawing = false;

function getPos(e) {

    const rect = canvas.getBoundingClientRect();

    if (e.touches) {

        return {
            x: e.touches[0].clientX - rect.left,
            y: e.touches[0].clientY - rect.top
        };

    }

    return {
        x: e.clientX - rect.left,
        y: e.clientY - rect.top
    };

}

function start(e){

    drawing = true;

    const pos = getPos(e);

    ctx.beginPath();
    ctx.moveTo(pos.x,pos.y);

}

function move(e){

    if(!drawing) return;

    e.preventDefault();

    const pos = getPos(e);

    ctx.lineTo(pos.x,pos.y);

    ctx.stroke();

}

function end(){

    drawing = false;

    document.getElementById('signature').value = canvas.toDataURL();

}

canvas.addEventListener('mousedown',start);
canvas.addEventListener('mousemove',move);
canvas.addEventListener('mouseup',end);
canvas.addEventListener('mouseleave',end);

canvas.addEventListener('touchstart',start);
canvas.addEventListener('touchmove',move,{passive:false});
canvas.addEventListener('touchend',end);

document.getElementById('clearSignature').onclick=function(){

    ctx.clearRect(0,0,canvas.width,canvas.height);

    document.getElementById('signature').value='';

};
</script>

</x-app-layout>
