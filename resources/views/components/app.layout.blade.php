<!DOCTYPE html>
<html lang="bg">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>OilTrack CRM</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="bg-slate-100">

<div class="min-h-screen flex">

    <div
        id="overlay"
        class="fixed inset-0 bg-black/60 hidden z-40 lg:hidden">
    </div>

    <aside
        id="sidebar"
        class="fixed inset-y-0 left-0 z-50 w-72 bg-slate-900 text-white shadow-2xl transform -translate-x-full lg:translate-x-0 transition-all duration-300 ease-in-out overflow-y-auto">

        <div class="h-20 flex items-center justify-between px-6 border-b border-slate-700">

            <h1 class="text-2xl font-bold tracking-wide">

                🛢 OilTrack

            </h1>

            <button
                id="closeSidebar"
                class="lg:hidden text-3xl">

                ✕

            </button>

        </div>

        <nav class="py-6 space-y-1">

            <a
                href="{{ route('dashboard') }}"
                class="flex items-center gap-3 px-7 py-4 rounded-r-xl transition {{ request()->routeIs('dashboard') ? 'bg-slate-800 border-r-4 border-blue-500' : 'hover:bg-slate-800' }}">

                🏠 Dashboard

            </a>

            @if(!Auth::user()->isDriver())

            <a
                href="{{ route('clients.index') }}"
                class="flex items-center gap-3 px-7 py-4 rounded-r-xl transition {{ request()->routeIs('clients.*') ? 'bg-slate-800 border-r-4 border-blue-500' : 'hover:bg-slate-800' }}">

                🏢 Обекти

            </a>

            @endif

            <a
                href="{{ route('routes.index') }}"
                class="flex items-center gap-3 px-7 py-4 rounded-r-xl transition {{ request()->routeIs('routes.*') ? 'bg-slate-800 border-r-4 border-blue-500' : 'hover:bg-slate-800' }}">

                🚛 Маршрути

            </a>

            <a
                href="{{ route('map.index') }}"
                class="flex items-center gap-3 px-7 py-4 rounded-r-xl transition {{ request()->routeIs('map.*') ? 'bg-slate-800 border-r-4 border-blue-500' : 'hover:bg-slate-800' }}">

                🗺️ Карта

            </a>

            @if(Auth::user()->isAdmin() || Auth::user()->isDispatcher())

            <a
                href="{{ route('vehicles.index') }}"
                class="flex items-center gap-3 px-7 py-4 rounded-r-xl transition {{ request()->routeIs('vehicles.*') ? 'bg-slate-800 border-r-4 border-blue-500' : 'hover:bg-slate-800' }}">

                🚚 Автомобили

            </a>

            @endif

            @if(Auth::user()->isAdmin())

            <a
                href="{{ route('users.index') }}"
                class="flex items-center gap-3 px-7 py-4 rounded-r-xl transition {{ request()->routeIs('users.*') ? 'bg-slate-800 border-r-4 border-blue-500' : 'hover:bg-slate-800' }}">

                👥 Потребители

            </a>

            @endif

            @if(!Auth::user()->isDriver())

            <a
                href="{{ route('clients.index') }}"
                class="flex items-center gap-3 px-7 py-4 rounded-r-xl transition {{ request()->routeIs('collections.*') ? 'bg-slate-800 border-r-4 border-blue-500' : 'hover:bg-slate-800' }}">

                🛢️ Събирания

            </a>

            @endif

            @if(Auth::user()->isAdmin() || Auth::user()->isDispatcher())

            <a
                href="#"
                class="flex items-center gap-3 px-7 py-4 rounded-r-xl hover:bg-slate-800">

                📊 Отчети

            </a>

            @endif

            @if(Auth::user()->isAdmin())

            <a
                href="#"
                class="flex items-center gap-3 px-7 py-4 rounded-r-xl hover:bg-slate-800">

                ⚙️ Настройки

            </a>

            @endif

            <div class="pt-6 px-6">

                <form
                    method="POST"
                    action="{{ route('logout') }}">

                    @csrf

                    <button
                        class="w-full bg-red-600 hover:bg-red-700 py-3 rounded-xl font-semibold transition">

                        🚪 Изход

                    </button>

                </form>

            </div>

        </nav>

    </aside>

    <div class="flex-1 flex flex-col min-w-0">

        <header class="sticky top-0 z-30 bg-white shadow">

            <div class="h-16 lg:h-20 px-4 lg:px-10 flex items-center justify-between">

                <div class="flex items-center gap-4">

                    <button
                        id="openSidebar"
                        class="lg:hidden text-3xl">

                        ☰

                    </button>

                    <div>

                        <h2 class="text-xl lg:text-3xl font-bold text-slate-800">

                            OilTrack CRM

                        </h2>

                        <p class="hidden lg:block text-gray-500">

                            Управление на отпадни масла

                        </p>

                    </div>

                </div>

                <div class="flex items-center gap-4">

                    <button class="text-2xl">

                        🔔

                    </button>

                    <div class="flex items-center gap-3">

                        <span class="text-3xl">

                            👤

                        </span>

                        <div class="hidden md:block">

                            <div class="font-semibold">

                                {{ Auth::user()->name }}

                            </div>

                            <div class="text-sm text-gray-500">

                                @if(Auth::user()->isAdmin())

                                    Администратор

                                @elseif(Auth::user()->isDispatcher())

                                    Диспечер

                                @else

                                    Шофьор

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </header>
        <main class="flex-1 p-4 lg:p-8 overflow-x-hidden">

    @isset($header)

        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">

            {{ $header }}

        </div>

    @endisset

    {{ $slot }}

</main>

</div>

</div>

<script>

const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');
const openSidebar = document.getElementById('openSidebar');
const closeSidebar = document.getElementById('closeSidebar');

function openMenu() {

    sidebar.classList.remove('-translate-x-full');
    overlay.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');

}

function closeMenu() {

    sidebar.classList.add('-translate-x-full');
    overlay.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');

}

if (openSidebar) {

    openSidebar.addEventListener('click', openMenu);

}

if (closeSidebar) {

    closeSidebar.addEventListener('click', closeMenu);

}

overlay.addEventListener('click', closeMenu);

window.addEventListener('resize', () => {

    if (window.innerWidth >= 1024) {

        sidebar.classList.remove('-translate-x-full');
        overlay.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');

    } else {

        sidebar.classList.add('-translate-x-full');

    }

});

</script>

</body>

</html>