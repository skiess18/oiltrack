<x-app-layout>

<x-slot name="header">

<div class="flex flex-col xl:flex-row xl:justify-between xl:items-center gap-5">

    <div>

        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-slate-800">

            🚛 Маршрути

        </h2>

        <p class="text-gray-500 mt-2">

            Управление на маршрутите за събиране

        </p>

    </div>

    <div class="flex flex-col sm:flex-row gap-3 w-full xl:w-auto">

        <a
            href="{{ route('routes.create') }}"
            class="bg-blue-600 hover:bg-blue-700 hover:scale-105 transition-all duration-300 text-white text-center font-semibold px-6 py-3 rounded-2xl shadow">

            ➕ Нов маршрут

        </a>

    </div>

</div>

</x-slot>

<div class="space-y-6">

@if(session('success'))

<div class="bg-green-500 text-white rounded-2xl shadow-lg p-5">

    {{ session('success') }}

</div>

@endif
<!-- Статистика -->

<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

    <div class="bg-white rounded-3xl shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 p-6">

        <div class="flex justify-between items-center">

            <div>

                <p class="text-gray-500 text-sm">

                    Общо маршрути

                </p>

                <h3 class="text-4xl font-bold mt-2 text-slate-800">

                    {{ $routes->count() }}

                </h3>

            </div>

            <div class="w-16 h-16 rounded-2xl bg-blue-100 flex items-center justify-center text-4xl">

                🚛

            </div>

        </div>

    </div>

    <div class="bg-white rounded-3xl shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 p-6">

        <div class="flex justify-between items-center">

            <div>

                <p class="text-gray-500 text-sm">

                    Планирани

                </p>

                <h3 class="text-4xl font-bold mt-2 text-yellow-500">

                    {{ $routes->where('status','planned')->count() }}

                </h3>

            </div>

            <div class="w-16 h-16 rounded-2xl bg-yellow-100 flex items-center justify-center text-4xl">

                📅

            </div>

        </div>

    </div>

    <div class="bg-white rounded-3xl shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 p-6">

        <div class="flex justify-between items-center">

            <div>

                <p class="text-gray-500 text-sm">

                    В процес

                </p>

                <h3 class="text-4xl font-bold mt-2 text-blue-600">

                    {{ $routes->where('status','in_progress')->count() }}

                </h3>

            </div>

            <div class="w-16 h-16 rounded-2xl bg-blue-100 flex items-center justify-center text-4xl">

                🚚

            </div>

        </div>

    </div>

    <div class="bg-white rounded-3xl shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 p-6">

        <div class="flex justify-between items-center">

            <div>

                <p class="text-gray-500 text-sm">

                    Завършени

                </p>

                <h3 class="text-4xl font-bold mt-2 text-green-600">

                    {{ $routes->where('status','completed')->count() }}

                </h3>

            </div>

            <div class="w-16 h-16 rounded-2xl bg-green-100 flex items-center justify-center text-4xl">

                ✅

            </div>

        </div>

    </div>

</div>
<!-- Desktop -->

<div class="hidden xl:block bg-white rounded-3xl shadow-md overflow-hidden">

    <table class="w-full">

        <thead class="bg-slate-100">

        <tr>

            <th class="px-6 py-5 text-left font-semibold">
                📅 Дата
            </th>

            <th class="px-6 py-5 text-left font-semibold">
                👤 Шофьор
            </th>

            <th class="px-6 py-5 text-left font-semibold">
                🚚 Автомобил
            </th>

            <th class="px-6 py-5 text-center font-semibold">
                🏢 Обекти
            </th>

            <th class="px-6 py-5 text-center font-semibold">
                📋 Статус
            </th>

            <th class="px-6 py-5 text-center font-semibold">
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

                {{ $route->driver->name ?? '-' }}

            </td>

            <td class="px-6 py-5">

                @if($route->vehicle)

                    {{ $route->vehicle->registration }}

                @else

                    —

                @endif

            </td>

            <td class="px-6 py-5 text-center">

                <span class="bg-slate-100 px-4 py-2 rounded-full font-semibold">

                    {{ $route->clients->count() }}

                </span>

            </td>

            <td class="px-6 py-5 text-center">

                @if($route->status=='planned')

                    <span class="bg-yellow-100 text-yellow-700 px-4 py-2 rounded-full font-semibold">

                        📅 Планиран

                    </span>

                @elseif($route->status=='in_progress')

                    <span class="bg-blue-100 text-blue-700 px-4 py-2 rounded-full font-semibold">

                        🚚 В процес

                    </span>

                @elseif($route->status=='completed')

                    <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full font-semibold">

                        ✅ Завършен

                    </span>

                @else

                    <span class="bg-red-100 text-red-700 px-4 py-2 rounded-full font-semibold">

                        ❌ Отменен

                    </span>

                @endif

            </td>

            <td class="px-6 py-5">

                <div class="flex justify-center gap-3">

                    <a
                        href="{{ route('routes.show',$route) }}"
                        class="w-11 h-11 rounded-xl bg-blue-600 hover:bg-blue-700 text-white flex items-center justify-center transition">

                        👁️

                    </a>

                    <a
                        href="{{ route('routes.edit',$route) }}"
                        class="w-11 h-11 rounded-xl bg-yellow-500 hover:bg-yellow-600 text-white flex items-center justify-center transition">

                        ✏️

                    </a>

                    @if($route->status!='completed')

                    <a
                        href="{{ route('routes.drive',$route) }}"
                        class="w-11 h-11 rounded-xl bg-green-600 hover:bg-green-700 text-white flex items-center justify-center transition">

                        ▶️

                    </a>

                    @endif

                    <form
                        action="{{ route('routes.destroy',$route) }}"
                        method="POST">

                        @csrf
                        @method('DELETE')

                        <button
                            onclick="return confirm('Да се изтрие ли маршрутът?')"
                            class="w-11 h-11 rounded-xl bg-red-600 hover:bg-red-700 text-white">

                            🗑️

                        </button>

                    </form>

                </div>

            </td>

        </tr>

        @empty

        <tr>

            <td colspan="6" class="py-16 text-center text-gray-500">

                Няма създадени маршрути.

            </td>

        </tr>

        @endforelse

        </tbody>

    </table>

</div>
<!-- Mobile Cards -->

<div class="xl:hidden space-y-5">

@forelse($routes as $route)

<div class="bg-white rounded-3xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">

    <div class="p-5">

        <div class="flex justify-between items-start gap-4">

            <div class="flex-1">

                <h3 class="text-xl font-bold text-slate-800">

                    🚛 Маршрут

                </h3>

                <div class="mt-3 space-y-2 text-gray-600">

                    <div>

                        📅 {{ \Carbon\Carbon::parse($route->route_date)->format('d.m.Y') }}

                    </div>

                    <div>

                        👤 {{ $route->driver->name ?? '-' }}

                    </div>

                    <div>

                        🚚 {{ $route->vehicle->registration ?? '-' }}

                    </div>

                    <div>

                        🏢 {{ $route->clients->count() }} обекта

                    </div>

                    @if($route->notes)

                    <div>

                        📝 {{ $route->notes }}

                    </div>

                    @endif

                </div>

            </div>

            <div>

                @if($route->status=='planned')

                    <span class="bg-yellow-100 text-yellow-700 text-xs font-bold px-3 py-2 rounded-full">

                        📅 Планиран

                    </span>

                @elseif($route->status=='in_progress')

                    <span class="bg-blue-100 text-blue-700 text-xs font-bold px-3 py-2 rounded-full">

                        🚚 В процес

                    </span>

                @elseif($route->status=='completed')

                    <span class="bg-green-100 text-green-700 text-xs font-bold px-3 py-2 rounded-full">

                        ✅ Завършен

                    </span>

                @else

                    <span class="bg-red-100 text-red-700 text-xs font-bold px-3 py-2 rounded-full">

                        ❌ Отменен

                    </span>

                @endif

            </div>

        </div>

    </div>

    <div class="grid grid-cols-2 gap-px bg-slate-200">

        <a
            href="{{ route('routes.show',$route) }}"
            class="bg-blue-600 hover:bg-blue-700 text-white text-center py-4 font-semibold transition">

            👁️ Преглед

        </a>

        <a
            href="{{ route('routes.edit',$route) }}"
            class="bg-yellow-500 hover:bg-yellow-600 text-white text-center py-4 font-semibold transition">

            ✏️ Редакция

        </a>

        @if($route->status!='completed')

        <a
            href="{{ route('routes.drive',$route) }}"
            class="bg-green-600 hover:bg-green-700 text-white text-center py-4 font-semibold transition">

            ▶️ Стартирай

        </a>

        @else

        <div
            class="bg-gray-400 text-white text-center py-4 font-semibold">

            ✔️ Готов

        </div>

        @endif

        <form
            action="{{ route('routes.destroy',$route) }}"
            method="POST">

            @csrf
            @method('DELETE')

            <button
                onclick="return confirm('Да се изтрие ли маршрутът?')"
                class="w-full bg-red-600 hover:bg-red-700 text-white py-4 font-semibold transition">

                🗑️ Изтрий

            </button>

        </form>

    </div>

</div>

@empty

<div class="bg-white rounded-3xl shadow-md p-10 text-center text-gray-500">

    🚛 Няма създадени маршрути.

</div>

@endforelse

</div>
@if($routes->hasPages())

<div class="bg-white rounded-3xl shadow-md p-6">

    <div class="flex justify-center">

        {{ $routes->links() }}

    </div>

</div>

@endif

</div>

<style>

tbody tr{

    transition:all .25s;

}

tbody tr:hover{

    transform:scale(1.01);

}

.route-card{

    transition:all .25s;

}

.route-card:hover{

    transform:translateY(-4px);

}

@media(max-width:768px){

    .route-card{

        border-radius:24px;

    }

}

</style>

</x-app-layout>