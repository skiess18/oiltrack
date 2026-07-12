<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OilTrack</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

<div class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-72 bg-gray-900 text-white">

        <div class="p-6 text-3xl font-bold border-b border-gray-700">
            🛢 OilTrack
        </div>

        <nav class="mt-6">

            <a href="{{ route('dashboard') }}"
               class="block px-6 py-4 hover:bg-gray-800">
                🏠 Dashboard
            </a>

            <a href="{{ route('clients.index') }}"
               class="block px-6 py-4 hover:bg-gray-800">
                🏢 Обекти
            </a>

            <a href="#"
               class="block px-6 py-4 hover:bg-gray-800">
                🛢️ Събирания
            </a>

            <a href="#"
               class="block px-6 py-4 hover:bg-gray-800">
                🚛 Маршрути
            </a>

            <a href="#"
               class="block px-6 py-4 hover:bg-gray-800">
                🚚 Автомобили
            </a>

            <a href="#"
               class="block px-6 py-4 hover:bg-gray-800">
                👷 Служители
            </a>

            <a href="#"
               class="block px-6 py-4 hover:bg-gray-800">
                📊 Отчети
            </a>

            <a href="#"
               class="block px-6 py-4 hover:bg-gray-800">
                ⚙️ Настройки
            </a>

        </nav>

    </aside>

    <!-- Main Content -->
    <div class="flex-1">

        <header class="bg-white shadow px-8 py-5 flex justify-between items-center">

            <h1 class="text-2xl font-bold">
                OilTrack CRM
            </h1>

            <div class="font-semibold">
                {{ Auth::user()->name }}
            </div>

        </header>

        <main class="p-8">
            {{ $slot }}
        </main>

    </div>

</div>

</body>
</html>