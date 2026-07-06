<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Добавяне на обект
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">

                <h1 class="text-2xl font-bold mb-6">
                    Нов обект
                </h1>

                <form action="{{ route('clients.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block mb-2 font-semibold">Име</label>
                        <input
                            type="text"
                            name="name"
                            class="w-full border rounded-lg p-3"
                            required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-semibold">Адрес</label>
                        <input
                            type="text"
                            name="address"
                            class="w-full border rounded-lg p-3"
                            required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-semibold">Телефон</label>
                        <input
                            type="text"
                            name="phone"
                            class="w-full border rounded-lg p-3">
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-semibold">Лице за контакт</label>
                        <input
                            type="text"
                            name="contact_person"
                            class="w-full border rounded-lg p-3">
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-semibold">Капацитет (литра)</label>
                        <input
                            type="number"
                            name="capacity"
                            class="w-full border rounded-lg p-3"
                            value="0">
                    </div>

                    <div class="mb-6">
                        <label class="block mb-2 font-semibold">Бележки</label>
                        <textarea
                            name="notes"
                            rows="4"
                            class="w-full border rounded-lg p-3"></textarea>
                    </div>

                    <button
                        type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">
                        Запази
                    </button>

                    <a
                        href="{{ route('clients.index') }}"
                        class="ml-3 text-gray-600">
                        Отказ
                    </a>

                </form>

            </div>

        </div>
    </div>
</x-app-layout>