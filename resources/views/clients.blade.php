<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Обекти
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 bg-green-500 text-white p-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">

                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Списък с обекти</h1>

                    <a href="{{ route('clients.create') }}"
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        + Добави обект
                    </a>
                </div>

                <table class="w-full border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left p-3">Име</th>
                            <th class="text-left p-3">Адрес</th>
                            <th class="text-left p-3">Телефон</th>
                            <th class="text-left p-3">Контакт</th>
                            <th class="text-left p-3">Капацитет</th>
                            <th class="text-left p-3">Бележки</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($clients as $client)
                            <tr class="border-b">
                                <td class="p-3">{{ $client->name }}</td>
                                <td class="p-3">{{ $client->address }}</td>
                                <td class="p-3">{{ $client->phone }}</td>
                                <td class="p-3">{{ $client->contact_person }}</td>
                                <td class="p-3">{{ $client->capacity }}</td>
                                <td class="p-3">{{ $client->notes }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center p-6">
                                    Все още няма добавени обекти.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>

        </div>
    </div>
</x-app-layout>