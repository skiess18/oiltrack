<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Обекти
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">

                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Списък с обекти</h1>

                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        + Добави обект
                    </button>
                </div>

                <table class="w-full border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left p-3">Име</th>
                            <th class="text-left p-3">Адрес</th>
                            <th class="text-left p-3">Телефон</th>
                            <th class="text-left p-3">Последно събиране</th>
                            <th class="text-left p-3">Литри</th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr class="border-b">
                            <td class="p-3">Ресторант При Иван</td>
                            <td class="p-3">Пловдив</td>
                            <td class="p-3">0888123456</td>
                            <td class="p-3">01.07.2026</td>
                            <td class="p-3">120 L</td>
                        </tr>

                        <tr class="border-b">
                            <td class="p-3">Happy Grill</td>
                            <td class="p-3">София</td>
                            <td class="p-3">0888555444</td>
                            <td class="p-3">03.07.2026</td>
                            <td class="p-3">85 L</td>
                        </tr>

                    </tbody>

                </table>

            </div>

        </div>
    </div>
</x-app-layout>