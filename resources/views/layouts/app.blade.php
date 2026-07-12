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

    <!-- Mobile Overlay -->

    <div
        id="overlay"
        class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden">
    </div>

    <!-- Sidebar -->

    <aside
        id="sidebar"
        class="fixed lg:relative inset-y-0 left-0 w-72 bg-slate-900 text-white transform -translate-x-full lg:translate-x-0 transition duration-300 z-50 shadow-2xl">

        <div class="h-20 flex items-center justify-between px-6 border-b border-slate-700">

            <h1 class="text-2xl font-bold">

                🛢 OilTrack

            </h1>

            <button
                id="closeSidebar"
                class="lg:hidden text-2xl">

                ✕

            </button>

        </div>

        <nav class="py-6 space-y-1">

            <a
                href="{{ route('dashboard') }}"
                class="flex items-center gap-3 px-7 py-4 transition rounded-r-xl {{ request()->routeIs('dashboard') ? 'bg-slate-800 border-r-4 border-blue-500' : 'hover:bg-slate-800' }}">

                🏠 Dashboard

            </a>

            <a
                href="{{ route('clients.index') }}"
                class="flex items-center gap-3 px-7 py-4 transition rounded-r-xl {{ request()->routeIs('clients.*') ? 'bg-slate-800 border-r-4 border-blue-500' : 'hover:bg-slate-800' }}">

                🏢 Обекти

            </a>

            <a
                href="{{ route('routes.index') }}"
                class="flex items-center gap-3 px-7 py-4 transition rounded-r-xl {{ request()->routeIs('routes.*') ? 'bg-slate-800 border-r-4 border-blue-500' : 'hover:bg-slate-800' }}">

                🚛 Маршрути

            </a>

            <a
                href="{{ route('map.index') }}"
                class="flex items-center gap-3 px-7 py-4 transition rounded-r-xl {{ request()->routeIs('map.*') ? 'bg-slate-800 border-r-4 border-blue-500' : 'hover:bg-slate-800' }}">

                🗺️ Карта

            </a>

            <a
                href="#"
                class="flex items-center gap-3 px-7 py-4 hover:bg-slate-800 rounded-r-xl">

                🛢️ Събирания

            </a>

            <a
                href="#"
                class="flex items-center gap-3 px-7 py-4 hover:bg-slate-800 rounded-r-xl">

                📊 Отчети

            </a>

            <a
                href="#"
                class="flex items-center gap-3 px-7 py-4 hover:bg-slate-800 rounded-r-xl">

                ⚙️ Настройки

            </a>

        </nav>

    </aside>

    <!-- Main -->

    <div class="flex-1 flex flex-col">

        <!-- Top Bar -->

        <header class="bg-white shadow">

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

                                Администратор

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </header>

        <main class="p-4 lg:p-8">

            @isset($header)

                <div class="bg-white rounded-2xl shadow-lg mb-6 lg:mb-8 p-6">

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

    if(openSidebar){

        openSidebar.addEventListener('click', openMenu);

    }

    if(closeSidebar){

        closeSidebar.addEventListener('click', closeMenu);

    }

    if(overlay){

        overlay.addEventListener('click', closeMenu);

    }

    window.addEventListener('resize', () => {

        if(window.innerWidth >= 1024){

            sidebar.classList.remove('-translate-x-full');
            overlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');

        }else{

            sidebar.classList.add('-translate-x-full');

        }

    });

</script>

</body>

</html>