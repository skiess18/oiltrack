<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-3xl font-bold text-slate-800">✉️ Email настройки</h2>
            <p class="text-gray-500 mt-1">Получателите се разделят със запетая, точка и запетая или нов ред.</p>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        @if($errors->any())
            <div class="mb-6 rounded-xl border border-red-300 bg-red-100 p-5 text-red-700"><ul class="list-disc ml-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif
        @if(session('success'))
            <div class="mb-6 rounded-xl bg-green-600 p-5 text-white">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('settings.email-notifications.update') }}" class="space-y-6 rounded-3xl bg-white p-8 shadow-xl">
            @csrf
            @method('PUT')
            <div>
                <label class="mb-2 block font-semibold">При приключване на обект — Admin получатели</label>
                <textarea name="collection_completed_recipients" rows="4" class="w-full rounded-xl border-slate-300">{{ old('collection_completed_recipients', implode("\n", $collectionRecipients)) }}</textarea>
            </div>
            <div>
                <label class="mb-2 block font-semibold">Документи при край на работния ден</label>
                <textarea name="end_of_day_documents_recipients" rows="4" class="w-full rounded-xl border-slate-300">{{ old('end_of_day_documents_recipients', implode("\n", $endOfDayRecipients)) }}</textarea>
            </div>
            <div>
                <label class="mb-2 block font-semibold">Пътен лист при край на работния ден</label>
                <textarea name="transport_report_recipients" rows="4" class="w-full rounded-xl border-slate-300">{{ old('transport_report_recipients', implode("\n", $transportRecipients)) }}</textarea>
            </div>
            <button class="w-full rounded-xl bg-blue-600 py-4 font-bold text-white hover:bg-blue-700">Запази настройките</button>
        </form>
    </div>
</x-app-layout>
