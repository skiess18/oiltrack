<x-app-layout>

    <x-slot name="header">
        <div class="flex justify-between items-center w-full">

            <div>
                <h2 class="text-3xl font-bold text-slate-800">
                    🚛 Маршрути
                </h2>

                <p class="text-gray-500 mt-1">
                    Управление на маршрутите за събиране
                </p>
            </div>

            <a href="{{ route('routes.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl shadow-lg font-semibold transition">
                ➕ Нов маршрут
            </a>

        </div>
    </x-slot>

    <div class="space-y-8">

        @if(session('success'))
            <div class="bg-green-500 text-white px-6 py-4 rounded-xl shadow">
                {{ session('success') }}
            </div>
        @endif

        <!-- Статистика -->

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

            <div class="bg-white rounded-2xl shadow-lg p-6">

                <div class="text-gray-500">
                    Общо маршрути
                </div>

                <div class="text-4xl font-bold mt-3">
                    {{ $routes->count() }}
                </div>

            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6">

                <div class="text-gray-500">
                    Планирани
                </div>

                <div class="text-4xl font-bold text-yellow-500 mt-3">
                    {{ $routes->where('status','planned')->count() }}
                </div>

            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6">

                <div class="text-gray-500">
                    В процес
                </div>

                <div class="text-4xl font-bold text-blue-600 mt-3">
                    {{ $routes->where('status','in_progress')->count() }}
                </div>

            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6">

                <div class="text-gray-500">
                    Завършени
                </div>

                <div class="text-4xl font-bold text-green-600 mt-3">
                    {{ $routes->where('status','completed')->count() }}
                </div>

            </div>

        </div>

        <!-- Таблица -->

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-slate-200">

            <table class="w-full">

                <thead class="bg-slate-50">

                    <tr>

                        <th class="text-left px-6 py-4 font-semibold">
                            📅 Дата
                        </th>

                        <th class="text-left px-6 py-4 font-semibold">
                            🚚 Шофьор
                        </th>

                        <th class="text-center px-6 py-4 font-semibold">
                            🏢 Обекти
                        </th>

                        <th class="text-center px-6 py-4 font-semibold">
                            📋 Статус
                        </th>

                        <th class="text-left px-6 py-4 font-semibold">
                            📝 Бележки
                        </th>

                        <th class="text-center px-6 py-4 font-semibold">
                            ⚙️ Действия
                        </th>

                    </tr>

                </thead>

                <tbody>

                @forelse($routes as $route)

                    <tr class="border-t hover:bg-slate-50 transition">

                        <td class="px-6 py-5 font-semibold">
                            {{ \Carbon\Carbon::parse($route->route_date)->format('d.m.Y') }}
                        </td>

                        <td class="px-6 py-5">
                            {{ $route->driver ?: '-' }}
                        </td>

                        <td class="text-center px-6 py-5">

                            <span class="bg-slate-100 px-3 py-1 rounded-full">

                                {{ $route->clients->count() }}

                            </span>

                        </td>

                        <td class="text-center px-6 py-5">

                            @switch($route->status)

                                @case('planned')

                                    <span class="bg-yellow-100 text-yellow-700 px-4 py-2 rounded-full text-sm font-semibold">
                                        Планиран
                                    </span>

                                @break

                                @case('in_progress')

                                    <span class="bg-blue-100 text-blue-700 px-4 py-2 rounded-full text-sm font-semibold">
                                        В процес
                                    </span>

                                @break

                                @case('completed')

                                    <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm font-semibold">
                                        Завършен
                                    </span>

                                @break

                                @default

                                    <span class="bg-red-100 text-red-700 px-4 py-2 rounded-full text-sm font-semibold">
                                        Отменен
                                    </span>

                            @endswitch

                        </td>

                        <td class="px-6 py-5 text-gray-600">
                            {{ $route->notes ?: '-' }}
                        </td>

                        <td class="px-6 py-5">

                            <div class="flex justify-center gap-2">

                                <a href="{{ route('routes.show',$route) }}"
                                   class="bg-blue-600 hover:bg-blue-700 text-white rounded-lg w-10 h-10 flex items-center justify-center">
                                    👁️
                                </a>

                                <a href="{{ route('routes.edit',$route) }}"
                                   class="bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg w-10 h-10 flex items-center justify-center">
                                    ✏️
                                </a>

                                <form action="{{ route('routes.destroy',$route) }}" method="POST">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        onclick="return confirm('Да се изтрие ли маршрутът?')"
                                        class="bg-red-600 hover:bg-red-700 text-white rounded-lg w-10 h-10">

                                        🗑️

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6" class="text-center py-16 text-gray-400">

                            🚛 Няма създадени маршрути.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</x-app-layout>