<!DOCTYPE html>
<html lang="bg">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>OilTrack CRM</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-100 overflow-x-hidden">

<div class="min-h-screen flex">

    <!-- Overlay -->
    <div
        id="overlay"
        class="fixed inset-0 bg-black/50 hidden z-40 lg:hidden">
    </div>

    <!-- Sidebar -->
    <aside
        id="sidebar"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-white shadow-2xl transform -translate-x-full transition-transform duration-300 lg:relative lg:translate-x-0 lg:flex-shrink-0">

        <!-- Logo -->
        <div class="h-16 lg:h-20 flex items-center justify-between px-6 border-b border-slate-700">

            <div class="flex items-center gap-3">
                <span class="text-3xl">🛢️</span>

                <div>
                    <div class="text-xl font-bold">
                        OilTrack
                    </div>

                    <div class="text-xs text-slate-400">
                        CRM System
                    </div>
                </div>
            </div>

            <button
                id="closeSidebar"
                class="lg:hidden text-2xl hover:text-red-400">
                ✕
            </button>

        </div>

        <!-- Navigation -->

        <nav class="py-5 px-3 space-y-2">

            <a
                href="{{ route('dashboard') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-600 shadow-lg' : 'hover:bg-slate-800' }}">

                <span class="text-xl">🏠</span>

                <span class="font-medium">
                    Dashboard
                </span>

            </a>

            @if(!Auth::user()->isDriver())

            <a
                href="{{ route('clients.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('clients.*') ? 'bg-blue-600 shadow-lg' : 'hover:bg-slate-800' }}">

                <span class="text-xl">🏢</span>

                <span class="font-medium">
                    Обекти
                </span>

            </a>

            @endif
                        <a
                href="{{ route('routes.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('routes.*') ? 'bg-blue-600 shadow-lg' : 'hover:bg-slate-800' }}">

                <span class="text-xl">🚛</span>

                <span class="font-medium">
                    Маршрути
                </span>

            </a>

            <a
                href="{{ route('map.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('map.*') ? 'bg-blue-600 shadow-lg' : 'hover:bg-slate-800' }}">

                <span class="text-xl">🗺️</span>

                <span class="font-medium">
                    Карта
                </span>

            </a>

            @if(!Auth::user()->isDriver())

            <a
                href="{{ route('clients.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('collections.*') ? 'bg-blue-600 shadow-lg' : 'hover:bg-slate-800' }}">

                <span class="text-xl">🛢️</span>

                <span class="font-medium">
                    Събирания
                </span>

            </a>

            @endif

            @if(Auth::user()->isAdmin() || Auth::user()->isDispatcher())

            <a
                href="{{ route('vehicles.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('vehicles.*') ? 'bg-blue-600 shadow-lg' : 'hover:bg-slate-800' }}">

                <span class="text-xl">🚚</span>

                <span class="font-medium">
                    Автомобили
                </span>

            </a>

            @endif

            @if(Auth::user()->isAdmin() || Auth::user()->isDriver())
            <a
                href="{{ route('transport-report.create') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('transport-report.*') ? 'bg-blue-600 shadow-lg' : 'hover:bg-slate-800' }}">

                <span class="text-xl">📝</span>

                <span class="font-medium">
                    Транспортен отчет
                </span>

            </a>

            <a
                href="{{ route('users.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('users.*') ? 'bg-blue-600 shadow-lg' : 'hover:bg-slate-800' }}">

                <span class="text-xl">👥</span>

                <span class="font-medium">
                    Потребители
                </span>

            </a>

            @endif

        </nav>

    </aside>

    <!-- Main -->
    <div class="flex-1 flex flex-col min-w-0">
                <!-- Header -->
        <header class="sticky top-0 z-30 bg-white/90 backdrop-blur border-b border-slate-200 shadow-sm">

            <div class="h-16 lg:h-20 px-4 lg:px-8 flex items-center justify-between">

                <!-- Left -->
                <div class="flex items-center gap-4 min-w-0">

                    <button
                        id="openSidebar"
                        class="lg:hidden w-11 h-11 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition">

                        ☰

                    </button>

                    <div class="min-w-0">

                        <h1 class="text-xl lg:text-3xl font-bold text-slate-800 truncate">

                            OilTrack CRM

                        </h1>

                        <p class="hidden lg:block text-sm text-slate-500">

                            Управление на отпадни масла

                        </p>

                    </div>

                </div>

                <!-- Right -->
                <div class="flex items-center gap-4">

                    <button
                        class="relative w-11 h-11 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition">

                        🔔

                        <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full"></span>

                    </button>

                    <div class="flex items-center gap-3 bg-slate-100 rounded-2xl px-4 py-2">

                        <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">

                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}

                        </div>

                        <div class="hidden md:block">

                            <div class="font-semibold text-slate-800">

                                {{ Auth::user()->name }}

                            </div>

                            <div class="text-xs text-slate-500">

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

        <!-- Content -->

        <main class="flex-1 overflow-x-hidden p-4 lg:p-8">

            @isset($header)

                <div class="bg-white rounded-3xl shadow-md p-6 mb-6">

                    {{ $header }}

                </div>

            @endisset

            {{ $slot }}

        </main>

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

if (overlay) {
    overlay.addEventListener('click', closeMenu);
}

document.querySelectorAll('#sidebar a').forEach(link => {
    link.addEventListener('click', () => {
        if (window.innerWidth < 1024) {
            closeMenu();
        }
    });
});

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeMenu();
    }
});

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
<style>

/* Общи настройки */
html,
body{
    overflow-x:hidden;
}

*{
    box-sizing:border-box;
}

img{
    max-width:100%;
    height:auto;
}

table{
    width:100%;
    border-collapse:collapse;
}

/* Scrollbar */
::-webkit-scrollbar{
    width:8px;
    height:8px;
}

::-webkit-scrollbar-track{
    background:#e2e8f0;
}

::-webkit-scrollbar-thumb{
    background:#64748b;
    border-radius:999px;
}

::-webkit-scrollbar-thumb:hover{
    background:#475569;
}

/* Sidebar */
#sidebar{
    overflow-y:auto;
}

/* Активен линк */
#sidebar a{
    transition:all .25s ease;
}

#sidebar a:hover{
    transform:translateX(4px);
}

/* Карти */
.bg-white{
    transition:all .25s ease;
}

.bg-white:hover{
    box-shadow:0 15px 35px rgba(0,0,0,.08);
}

/* Бутони */
button,
a{
    transition:all .25s ease;
}

/* Responsive */
@media (max-width:1023px){

    #sidebar{
        width:280px;
    }

    main{
        padding:16px;
    }

}

@media (min-width:1024px){

    main{
        padding:32px;
    }

}

</style>

</body>
</html>

