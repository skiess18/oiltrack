<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row justify-between lg:items-center gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-800">🏭 Склад</h2>
                <p class="text-gray-500 mt-1">Наличности и движения на събраното олио</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('warehouse.report') }}" target="_blank" class="bg-slate-700 hover:bg-slate-800 text-white px-5 py-3 rounded-xl">📄 Складов отчет</a>
                <a href="{{ route('warehouse.create') }}" class="bg-red-600 hover:bg-red-700 text-white px-5 py-3 rounded-xl">➖ Ново изписване</a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        @if(session('success'))
            <div class="bg-green-500 text-white rounded-2xl shadow-lg p-5">{{ session('success') }}</div>
        @endif

        <div class="bg-gradient-to-r from-blue-700 to-sky-600 rounded-3xl p-7 text-white shadow-xl">
            <div class="text-blue-100">Текуща складова наличност</div>
            <div class="text-5xl font-bold mt-2">{{ number_format($currentStock, 2) }} <span class="text-2xl">литра</span></div>
        </div>

        <div class="bg-white rounded-3xl shadow-md overflow-hidden">
            <div class="px-6 py-5 border-b bg-slate-50">
                <h3 class="text-2xl font-bold">📋 История на складовите движения</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-slate-100 text-slate-700">
                        <tr>
                            <th class="px-5 py-4 text-left">Дата</th>
                            <th class="px-5 py-4 text-center">Тип</th>
                            <th class="px-5 py-4 text-center">Литри</th>
                            <th class="px-5 py-4 text-left">Шофьор</th>
                            <th class="px-5 py-4 text-left">Купувач</th>
                            <th class="px-5 py-4 text-center">Оставаща наличност</th>
                            <th class="px-5 py-4 text-center">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movements as $movement)
                            <tr class="border-t hover:bg-slate-50">
                                <td class="px-5 py-4">{{ $movement['date']->format('d.m.Y') }}</td>
                                <td class="px-5 py-4 text-center">
                                    @if($movement['type'] === 'in')
                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full font-bold">IN</span>
                                    @else
                                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full font-bold">OUT</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-center font-semibold {{ $movement['type'] === 'in' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $movement['type'] === 'in' ? '+' : '-' }}{{ number_format($movement['liters'], 2) }}
                                </td>
                                <td class="px-5 py-4">{{ $movement['driver'] ?: '—' }}</td>
                                <td class="px-5 py-4">{{ $movement['buyer'] ?: '—' }}</td>
                                <td class="px-5 py-4 text-center font-bold">{{ number_format($movement['remaining_stock'], 2) }} L</td>
                                <td class="px-5 py-4 text-center">
                                    @if($movement['type'] === 'out')
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('warehouse.edit', $movement['transaction']) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-lg">✏️</a>
                                            <form method="POST" action="{{ route('warehouse.destroy', $movement['transaction']) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button onclick="return confirm('Да се изтрие ли транзакцията?')" class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg">🗑️</button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-gray-400">{{ $movement['reference'] }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-5 py-14 text-center text-gray-500">Все още няма складови движения.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
