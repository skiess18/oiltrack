<x-app-layout>

    <x-slot name="header">

        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">

            <div>

                <h2 class="text-3xl font-bold text-slate-800">
                    🛢️ Ново събиране
                </h2>

                <p class="text-gray-500">
                    Въвеждане на събраното количество
                </p>

            </div>

            <a href="{{ route('clients.show',$client) }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-xl">

                ← Назад

            </a>

        </div>

    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">

        @if ($errors->any())

            <div class="bg-red-100 border border-red-300 text-red-700 rounded-xl p-5">

                <ul class="list-disc ml-6">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif

        <div class="bg-white rounded-3xl shadow-xl p-8">

            <div class="text-center">

                <div class="text-6xl">

                    🏢

                </div>

                <h1 class="text-3xl font-bold mt-4">

                    {{ $client->name }}

                </h1>

                <p class="text-gray-500 mt-2">

                    {{ $client->address }}

                </p>

            </div>

            <form
                action="{{ route('collections.store',$client) }}"
                method="POST"
                class="mt-8 space-y-6">

                @csrf

                @if(request()->has('route'))

                    <input
                        type="hidden"
                        name="route_id"
                        value="{{ request('route') }}">

                @endif

                <div>

                    <label class="block font-semibold mb-2">

                        📅 Дата

                    </label>

                    <input
                        type="date"
                        name="collection_date"
                        value="{{ old('collection_date',date('Y-m-d')) }}"
                        class="w-full border rounded-xl px-4 py-4"
                        required>

                </div>

                <div>

                    <label class="block font-semibold mb-2">

                        🛢️ Литри

                    </label>

                    <input
                        id="liters"
                        type="number"
                        step="0.01"
                        min="0"
                        name="liters"
                        value="{{ old('liters') }}"
                        placeholder="Например 120"
                        class="w-full border rounded-xl px-4 py-5 text-2xl font-bold"
                        required>

                </div>

                <div>

                    <label class="block font-semibold mb-2">

                        💰 Цена за литър

                    </label>

                    <input
                        id="price"
                        type="number"
                        step="0.01"
                        min="0"
                        name="price_per_liter"
                        value="{{ old('price_per_liter',0) }}"
                        class="w-full border rounded-xl px-4 py-5 text-xl"
                        required>

                </div>
                                <div class="bg-slate-100 rounded-2xl p-6">

                    <div class="text-gray-500 mb-2">

                        💵 Обща сума

                    </div>

                    <div
                        id="total"
                        class="text-4xl font-bold text-green-600">

                        0.00 лв.

                    </div>

                </div>

                <div>

                    <label class="block font-semibold mb-2">

                        📝 Бележки

                    </label>

                    <textarea
                        name="notes"
                        rows="5"
                        class="w-full border rounded-xl px-4 py-4"
                        placeholder="Допълнителна информация...">{{ old('notes') }}</textarea>

                </div>

                <button
                    type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white rounded-2xl py-5 text-2xl font-bold shadow-lg">

                    💾 Запази събирането

                </button>

            </form>

        </div>

    </div>

<script>

const liters=document.getElementById('liters');
const price=document.getElementById('price');
const total=document.getElementById('total');

function calculate(){

    let l=parseFloat(liters.value)||0;
    let p=parseFloat(price.value)||0;

    total.innerHTML=(l*p).toFixed(2)+" лв.";

}

liters.addEventListener('input',calculate);
price.addEventListener('input',calculate);

calculate();

</script>

</x-app-layout>