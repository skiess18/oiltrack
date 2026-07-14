<x-app-layout>

<x-slot name="header">

<div class="flex items-center justify-between">

    <div>

        <h2 class="text-3xl font-bold text-slate-800">

            🚚 Начало на работния ден

        </h2>

        <p class="text-gray-500 mt-1">

            Попълнете транспортния отчет преди да започнете работа.

        </p>

    </div>

</div>

</x-slot>

<div class="max-w-3xl mx-auto">

    @if($errors->any())

        <div class="bg-red-100 border border-red-300 text-red-700 rounded-2xl p-5 mb-6">

            <ul class="list-disc ml-5">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    <div class="bg-white rounded-3xl shadow-xl p-8">

        <form
            method="POST"
            action="{{ route('transport-report.store') }}"
            class="space-y-8">

            @csrf

            <div>

                <label class="block font-semibold mb-2">

                    🚚 Автомобил

                </label>

                <select
                    name="vehicle_id"
                    required
                    class="w-full rounded-xl border-slate-300">

                    <option value="">

                        Изберете автомобил

                    </option>

                    @foreach($vehicles as $vehicle)

                        <option value="{{ $vehicle->id }}">

                            {{ $vehicle->registration_number }}

                        </option>

                    @endforeach

                </select>

            </div>

            <div class="grid md:grid-cols-2 gap-6">

                <div>

                    <label class="block font-semibold mb-2">

                        🔢 Начален километраж

                    </label>

                    <input
                        type="number"
                        name="start_km"
                        required
                        min="0"
                        class="w-full rounded-xl border-slate-300"
                        placeholder="Например 152430">

                </div>

                <div>

                    <label class="block font-semibold mb-2">

                        ⛽ Гориво (%)

                    </label>

                    <input
                        type="number"
                        name="start_fuel"
                        required
                        min="0"
                        max="100"
                        class="w-full rounded-xl border-slate-300"
                        placeholder="80">

                </div>

            </div>

            <div>

                <label class="block font-semibold mb-2">

                    📝 Бележка

                </label>

                <textarea
                    name="notes"
                    rows="4"
                    class="w-full rounded-xl border-slate-300"
                    placeholder="По желание..."></textarea>

            </div>

            <div class="pt-4">

                <button
                    class="w-full bg-green-600 hover:bg-green-700 text-white rounded-2xl py-4 text-lg font-bold shadow-lg">

                    ▶️ Започни работния ден

                </button>

            </div>

        </form>

    </div>

</div>

</x-app-layout>