<x-app-layout>

<x-slot name="header">

<div class="flex justify-between items-center">

    <div>

        <h2 class="text-3xl font-bold">

            ➕ Нов потребител

        </h2>

        <p class="text-gray-500">

            Създаване на нов акаунт

        </p>

    </div>

    <a
        href="{{ route('users.index') }}"
        class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-xl">

        ← Назад

    </a>

</div>

</x-slot>

@if($errors->any())

<div class="bg-red-100 border border-red-300 text-red-700 rounded-xl p-4 mb-6">

    <ul class="list-disc ml-6">

        @foreach($errors->all() as $error)

            <li>{{ $error }}</li>

        @endforeach

    </ul>

</div>

@endif

<div class="max-w-3xl mx-auto">

<div class="bg-white rounded-2xl shadow-lg p-8">

<form
    action="{{ route('users.store') }}"
    method="POST">

    @csrf

    <div class="mb-5">

        <label class="block font-semibold mb-2">

            👤 Име

        </label>

        <input
            type="text"
            name="name"
            value="{{ old('name') }}"
            class="w-full border rounded-xl px-4 py-3"
            required>

    </div>

    <div class="mb-5">

        <label class="block font-semibold mb-2">

            📧 Имейл

        </label>

        <input
            type="email"
            name="email"
            value="{{ old('email') }}"
            class="w-full border rounded-xl px-4 py-3"
            required>

    </div>

    <div class="mb-5">

        <label class="block font-semibold mb-2">

            🔑 Парола

        </label>

        <input
            type="password"
            name="password"
            class="w-full border rounded-xl px-4 py-3"
            required>

    </div>

    <div class="mb-5">

        <label class="block font-semibold mb-2">

            🔑 Потвърди паролата

        </label>

        <input
            type="password"
            name="password_confirmation"
            class="w-full border rounded-xl px-4 py-3"
            required>

    </div>

    <div class="mb-8">

        <label class="block font-semibold mb-2">

            🎯 Роля

        </label>

        <select
            name="role"
            class="w-full border rounded-xl px-4 py-3"
            required>

            <option value="driver">🚛 Шофьор</option>

            <option value="dispatcher">📋 Диспечер</option>

            <option value="admin">👑 Администратор</option>

        </select>

    </div>

    <button
        type="submit"
        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-xl text-lg font-bold">

        💾 Създай потребител

    </button>

</form>

</div>

</div>

</x-app-layout>