<x-app-layout>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-slate-800">
                    📊 Статистика
                </h2>

                <p class="text-gray-500 mt-1">
                    Статистика за събиранията
                </p>
            </div>

            <a href="{{ route('dashboard') }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-3 rounded-xl shadow">
                ← Dashboard
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">

        <div class="bg-white rounded-3xl shadow-md p-6">

            <div class="flex flex-wrap gap-3">

                <a href="{{ route('statistics.index',['period'=>'week']) }}"
                   class="{{ $period=='week' ? 'bg-blue-600 text-white' : 'bg-gray-100' }} px-5 py-3 rounded-xl font-semibold">
                    📅 Седмица
                </a>

                <a href="{{ route('statistics.index',['period'=>'month']) }}"
                   class="{{ $period=='month' ? 'bg-blue-600 text-white' : 'bg-gray-100' }} px-5 py-3 rounded-xl font-semibold">
                    📆 Месец
                </a>

                <a href="{{ route('statistics.index',['period'=>'year']) }}"
                   class="{{ $period=='year' ? 'bg-blue-600 text-white' : 'bg-gray-100' }} px-5 py-3 rounded-xl font-semibold">
                    🗓️ Година
                </a>

                <a href="{{ route('statistics.index',['period'=>'all']) }}"
                   class="{{ $period=='all' ? 'bg-blue-600 text-white' : 'bg-gray-100' }} px-5 py-3 rounded-xl font-semibold">
                    ♾️ Всичко
                </a>

            </div>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="bg-white rounded-3xl shadow-md p-6">

                <div class="text-gray-500">
                    Общо литри
                </div>

                <div class="text-5xl font-bold text-green-600 mt-4">
                    {{ number_format($totalLiters,2) }}
                </div>

                <div class="text-gray-400 mt-2">
                    литра
                </div>

            </div>

            <div class="bg-white rounded-3xl shadow-md p-6">

                <div class="text-gray-500">
                    Общо платени
                </div>

                <div class="text-5xl font-bold text-blue-600 mt-4">
                    {{ number_format($totalPaid,2) }}
                </div>

                <div class="text-gray-400 mt-2">
                    лв.
                </div>

            </div>

            <div class="bg-white rounded-3xl shadow-md p-6">

                <div class="text-gray-500">
                    Събирания
                </div>

                <div class="text-5xl font-bold mt-4">
                    {{ $collectionsCount }}
                </div>

            </div>

        </div>

    </div>

</x-app-layout>