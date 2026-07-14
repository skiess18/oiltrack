<x-app-layout>

<x-slot name="header">

<div class="flex justify-between items-center">

    <div>

        <h2 class="text-3xl font-bold text-slate-800">

            🏁 Край на работния ден

        </h2>

        <p class="text-gray-500 mt-1">

            Попълнете крайните данни за автомобила.

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
action="{{ route('transport-report.update') }}"
enctype="multipart/form-data"
class="space-y-8">

@csrf
@method('PUT')

<div class="grid md:grid-cols-2 gap-6">

<div>

<label class="block font-semibold mb-2">

🔢 Краен километраж

</label>

<input
type="number"
name="end_km"
required
min="{{ $report->start_km }}"
class="w-full rounded-xl border-slate-300"
placeholder="Например 152690">

</div>

<div>

<label class="block font-semibold mb-2">

⛽ Крайно гориво (%)

</label>

<input
type="number"
name="end_fuel"
required
min="0"
max="100"
class="w-full rounded-xl border-slate-300"
placeholder="55">

</div>

</div>

<div>

<label class="block font-semibold mb-2">

📷 Касова бележка (по желание)

</label>

<input
type="file"
name="receipt"
accept="image/*"
class="w-full rounded-xl border-slate-300">

</div>

<div>

<label class="block font-semibold mb-2">

📝 Бележка

</label>

<textarea
name="notes"
rows="4"
class="w-full rounded-xl border-slate-300"
placeholder="Допълнителна информация...">{{ $report->notes }}</textarea>

</div>

<div class="bg-slate-100 rounded-2xl p-5">

<div class="flex justify-between">

<span>Начален километраж</span>

<strong>{{ number_format($report->start_km) }} km</strong>

</div>

<div class="flex justify-between mt-3">

<span>Начално гориво</span>

<strong>{{ $report->start_fuel }}%</strong>

</div>

</div>

<div>

<button
class="w-full bg-red-600 hover:bg-red-700 text-white py-4 rounded-2xl text-lg font-bold shadow-lg">

🏁 Приключи работния ден

</button>

</div>

</form>

</div>

</div>

</x-app-layout>