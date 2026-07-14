<x-app-layout>

    <x-slot name="header">

        <div class="flex justify-between items-center">

            <div>

                <h2 class="text-3xl font-bold">

                    🚚 {{ $vehicle->brand }} {{ $vehicle->model }}

                </h2>

                <p class="text-gray-500">

                    Рег. № {{ $vehicle->registration }}

                </p>

            </div>

            <div class="flex gap-3">

                <a
                    href="{{ route('vehicles.edit',$vehicle) }}"
                    class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-xl">

                    ✏️ Редакция

                </a>

                <a
                    href="{{ route('vehicles.index') }}"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-xl">

                    ← Назад

                </a>

            </div>

        </div>

    </x-slot>

    <div class="max-w-6xl mx-auto grid lg:grid-cols-3 gap-6">

        <div class="bg-white rounded-3xl shadow-xl overflow-hidden">

            @if($vehicle->photo)

                <img
                    src="{{ asset('storage/'.$vehicle->photo) }}"
                    class="w-full h-80 object-cover">

            @else

                <div class="h-80 flex items-center justify-center bg-slate-200 text-8xl">

                    🚚

                </div>

            @endif

        </div>

        <div class="lg:col-span-2">

            <div class="bg-white rounded-3xl shadow-xl p-8">

                <h3 class="text-2xl font-bold mb-6">

                    Информация

                </h3>

                <div class="grid md:grid-cols-2 gap-5">

                    <div class="flex justify-between border-b pb-2">

                        <span>Марка</span>

                        <strong>{{ $vehicle->brand }}</strong>

                    </div>

                    <div class="flex justify-between border-b pb-2">

                        <span>Модел</span>

                        <strong>{{ $vehicle->model }}</strong>

                    </div>

                    <div class="flex justify-between border-b pb-2">

                        <span>Година</span>

                        <strong>{{ $vehicle->year ?: '-' }}</strong>

                    </div>

                    <div class="flex justify-between border-b pb-2">

                        <span>Регистрационен №</span>

                        <strong>{{ $vehicle->registration }}</strong>

                    </div>

                    <div class="flex justify-between border-b pb-2">

                        <span>VIN</span>

                        <strong>{{ $vehicle->vin ?: '-' }}</strong>

                    </div>

                    <div class="flex justify-between border-b pb-2">

                        <span>Цвят</span>

                        <strong>{{ $vehicle->color ?: '-' }}</strong>

                    </div>

                    <div class="flex justify-between border-b pb-2">

                        <span>Шофьор</span>

                        <strong>{{ $vehicle->driver ?: '-' }}</strong>

                    </div>

                    <div class="flex justify-between border-b pb-2">

                        <span>Разход</span>

                        <strong>

                            {{ $vehicle->fuel_consumption ?: '-' }}

                            @if($vehicle->fuel_consumption)

                                л/100 км

                            @endif

                        </strong>

                    </div>

                    <div class="flex justify-between border-b pb-2">

                        <span>Километри</span>

                        <strong>

                            {{ number_format($vehicle->current_km,0,' ',' ') }}

                        </strong>

                    </div>

                    <div class="flex justify-between border-b pb-2">

                        <span>Последен сервиз</span>

                        <strong>

                            {{ $vehicle->last_service ?: '-' }}

                        </strong>

                    </div>

                    <div class="flex justify-between border-b pb-2">

                        <span>Следващ сервиз</span>

                        <strong>

                            {{ $vehicle->next_service_km ?: '-' }}

                            @if($vehicle->next_service_km)

                                км

                            @endif

                        </strong>

                    </div>

                    <div class="flex justify-between border-b pb-2">

                        <span>ГТП</span>

                        <strong>

                            {{ $vehicle->inspection_date ?: '-' }}

                        </strong>

                    </div>

                    <div class="flex justify-between border-b pb-2">

                        <span>Гражданска</span>

                        <strong>

                            {{ $vehicle->insurance_date ?: '-' }}

                        </strong>

                    </div>

                    <div class="flex justify-between border-b pb-2">

                        <span>Статус</span>

                        <strong>

                            @switch($vehicle->status)

                                @case('active')

                                    🟢 Активен

                                    @break

                                @case('service')

                                    🟡 В сервиз

                                    @break

                                @default

                                    🔴 Неактивен

                            @endswitch

                        </strong>

                    </div>

                </div>

                @if($vehicle->notes)

                    <div class="mt-8">

                        <h3 class="font-bold text-xl mb-3">

                            📝 Бележки

                        </h3>

                        <div class="bg-slate-100 rounded-xl p-5">

                            {{ $vehicle->notes }}

                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>