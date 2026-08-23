<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="text-3xl font-bold text-slate-800">Редакция на събиране</h2>
                <p class="text-gray-500">{{ $collection->client->name }}</p>
            </div>

            <a href="{{ route('collections.show', $collection) }}" class="rounded-xl bg-gray-600 px-6 py-3 text-white hover:bg-gray-700">
                ← Назад
            </a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-4xl space-y-6">
        @if ($errors->any())
            <div class="rounded-xl border border-red-300 bg-red-100 p-5 text-red-700">
                <ul class="ml-6 list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('collections.update', $collection) }}" method="POST" class="space-y-6 rounded-3xl bg-white p-8 shadow-xl">
            @csrf
            @method('PUT')

            <div>
                <label class="mb-2 block font-semibold">📅 Дата</label>
                <input type="date" name="collection_date" value="{{ old('collection_date', $collection->collection_date->format('Y-m-d')) }}" class="w-full rounded-xl border px-4 py-4" required>
            </div>

            <div>
                <label class="mb-2 block font-semibold">🛢️ Литри</label>
                <input id="liters" type="number" name="liters" min="0" step="0.01" value="{{ old('liters', $collection->liters) }}" class="w-full rounded-xl border px-4 py-5 text-2xl font-bold" required>
            </div>

            <div class="rounded-2xl bg-slate-100 p-6">
                <div class="mb-2 text-gray-500">💰 Цена за литър</div>
                <div class="text-2xl font-bold text-slate-800">{{ number_format($collection->client->price_per_liter, 2) }} лв.</div>
            </div>

            <div class="rounded-2xl bg-green-50 p-6">
                <div class="mb-2 text-gray-500">💵 Обща сума</div>
                <div id="total" class="text-4xl font-bold text-green-600"></div>
            </div>

            <div>
                <label class="mb-2 block font-semibold">📝 Бележки</label>
                <textarea name="notes" rows="5" class="w-full rounded-xl border px-4 py-4">{{ old('notes', $collection->notes) }}</textarea>
            </div>

            <button type="submit" class="w-full rounded-2xl bg-green-600 py-5 text-2xl font-bold text-white shadow-lg hover:bg-green-700">
                💾 Запази промените
            </button>
        </form>
    </div>

    <script>
        const liters = document.getElementById('liters');
        const total = document.getElementById('total');
        const pricePerLiter = {{ Js::from((float) $collection->client->price_per_liter) }};

        const calculate = () => {
            total.textContent = `${((parseFloat(liters.value) || 0) * pricePerLiter).toFixed(2)} лв.`;
        };

        liters.addEventListener('input', calculate);
        calculate();
    </script>
</x-app-layout>
