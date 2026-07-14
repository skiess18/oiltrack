<x-app-layout>

    <x-slot name="header">

        <div class="flex justify-between items-center">

            <div>

                <h2 class="text-3xl font-bold">
                    🚚 Автомобили
                </h2>

                <p class="text-gray-500">
                    Управление на автомобилния парк
                </p>

            </div>

            <a
                href="{{ route('vehicles.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl shadow">

                ➕ Нов автомобил

            </a>

        </div>

    </x-slot>

    <div class="max-w-7xl mx-auto">

        @if(session('success'))

            <div class="mb-6 bg-green-100 border border-green-300 text-green-700 rounded-xl p-4">

                {{ session('success') }}

            </div>

        @endif

        @if($vehicles->count())

            <div class="grid lg:grid-cols-3 md:grid-cols-2 gap-6">

                @foreach($vehicles as $vehicle)

                    <div class="bg-white rounded-3xl shadow-xl overflow-hidden">

                        @if($vehicle->photo)

                            <img
                                src="{{ asset('storage/'.$vehicle->photo) }}"
                                class="w-full h-52 object-cover">

                        @else

                            <div class="h-52 flex items-center justify-center bg-slate-200 text-7xl">

                                🚚

                            </div>

                        @endif

                        <div class="p-6">

                            <div class="flex justify-between items-center">

                                <div>

                                    <h3 class="text-2xl font-bold">

                                        {{ $vehicle->brand }}

                                        {{ $vehicle->model }}

                                    </h3>

                                    <div class="text-gray-500 mt-1">

                                        {{ $vehicle->registration }}

                                    </div>

                                </div>

                                @switch($vehicle->status)

                                    @case('active')

                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-bold">

                                            🟢 Активен

                                        </span>

                                        @break

                                    @case('service')

                                        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm font-bold">

                                            🔧 Сервиз

                                        </span>

                                        @break

                                    @default

                                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-bold">

                                            🔴 Неактивен

                                        </span>

                                @endswitch

                            </div>

                            <div class="mt-6 space-y-2">

                                <div class="flex justify-between">

                                    <span class="text-gray-500">

                                        👤 Шофьор

                                    </span>

                                    <strong>

                                        {{ $vehicle->driver ?: '-' }}

                                    </strong>

                                </div>

                                <div class="flex justify-between">

                                    <span class="text-gray-500">

                                        ⛽ Разход

                                    </span>

                                    <strong>

                                        {{ $vehicle->fuel_consumption ?: '-' }}

                                        @if($vehicle->fuel_consumption)

                                            л/100км

                                        @endif

                                    </strong>

                                </div>

                                <div class="flex justify-between">

                                    <span class="text-gray-500">

                                        🚘 Километри

                                    </span>

                                    <strong>

                                        {{ number_format($vehicle->current_km,0,' ',' ') }}

                                    </strong>

                                </div>

                            </div>

                            <div class="grid grid-cols-3 gap-3 mt-8">

                                <a
                                    href="{{ route('vehicles.show',$vehicle) }}"
                                    class="bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl text-center">

                                    👁️

                                </a>

                                <a
                                    href="{{ route('vehicles.edit',$vehicle) }}"
                                    class="bg-orange-500 hover:bg-orange-600 text-white py-3 rounded-xl text-center">

                                    ✏️

                                </a>

                                <form
                                    action="{{ route('vehicles.destroy',$vehicle) }}"
                                    method="POST"
                                    onsubmit="return confirm('Сигурни ли сте?')">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        class="w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-xl">

                                        🗑️

                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

            <div class="mt-8">

                {{ $vehicles->links() }}

            </div>

        @else

            <div class="bg-white rounded-3xl shadow-xl p-20 text-center">

                <div class="text-8xl">

                    🚚

                </div>

                <h2 class="text-3xl font-bold mt-6">

                    Все още няма автомобили

                </h2>

                <p class="text-gray-500 mt-3">

                    Добавете първия автомобил.

                </p>

                <a
                    href="{{ route('vehicles.create') }}"
                    class="inline-block mt-8 bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-xl">

                    ➕ Добави автомобил

                </a>

            </div>

        @endif

    </div>

</x-app-layout>