<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-800">✏️ Редакция на складова транзакция</h2>
                <p class="text-gray-500 mt-1">Налични за тази корекция: {{ number_format($availableStock, 2) }} L</p>
            </div>
            <a href="{{ route('warehouse.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-3 rounded-xl">← Назад</a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto bg-white rounded-3xl shadow-xl p-8">
        @if($errors->any())
            <div class="bg-red-100 border border-red-300 text-red-700 rounded-xl p-5 mb-6">
                <ul class="list-disc ml-6">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form method="POST" action="{{ route('warehouse.update', $warehouseTransaction) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div><label class="block font-semibold mb-2">📅 Дата</label><input type="date" name="date" value="{{ old('date', $warehouseTransaction->date->format('Y-m-d')) }}" class="w-full border rounded-xl p-3" required></div>
            <div><label class="block font-semibold mb-2">🛢️ Количество (литри)</label><input type="number" name="quantity" value="{{ old('quantity', $warehouseTransaction->quantity) }}" min="0.01" step="0.01" max="{{ $availableStock }}" class="w-full border rounded-xl p-3" required></div>
            <div><label class="block font-semibold mb-2">🏭 Купувач / рециклираща компания</label><input type="text" name="buyer" value="{{ old('buyer', $warehouseTransaction->buyer) }}" class="w-full border rounded-xl p-3" required></div>
            <div><label class="block font-semibold mb-2">📄 Номер на документ</label><input type="text" name="document_number" value="{{ old('document_number', $warehouseTransaction->document_number) }}" class="w-full border rounded-xl p-3"></div>
            <div><label class="block font-semibold mb-2">📝 Бележки</label><textarea name="notes" rows="4" class="w-full border rounded-xl p-3">{{ old('notes', $warehouseTransaction->notes) }}</textarea></div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-xl font-bold">💾 Запази промените</button>
        </form>
    </div>
</x-app-layout>
