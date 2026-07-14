<x-app-layout>

<x-slot name="header">

<div class="flex justify-between items-center">

    <div>

        <h2 class="text-3xl font-bold">

            ✏️ Редакция на потребител

        </h2>

        <p class="text-gray-500">

            Промяна на информацията

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
action="{{ route('users.update',$user) }}"
method="POST">

@csrf
@method('PUT')

<div class="mb-5">

<label class="block font-semibold mb-2">

👤 Име

</label>

<input
type="text"
name="name"
value="{{ old('name',$user->name) }}"
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
value="{{ old('email',$user->email) }}"
class="w-full border rounded-xl px-4 py-3"
required>

</div>

<div class="mb-5">

<label class="block font-semibold mb-2">

🔑 Нова парола

</label>

<input
type="password"
name="password"
class="w-full border rounded-xl px-4 py-3">

<p class="text-sm text-gray-500 mt-2">

Остави празно ако не желаеш да сменяш паролата.

</p>

</div>

<div class="mb-8">

<label class="block font-semibold mb-2">

🎯 Роля

</label>

<select
name="role"
class="w-full border rounded-xl px-4 py-3">

<option value="driver" {{ $user->role=='driver'?'selected':'' }}>

🚛 Шофьор

</option>

<option value="dispatcher" {{ $user->role=='dispatcher'?'selected':'' }}>

📋 Диспечер

</option>

<option value="admin" {{ $user->role=='admin'?'selected':'' }}>

👑 Администратор

</option>

</select>

</div>

<button
type="submit"
class="w-full bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-xl text-lg font-bold">

💾 Запази промените

</button>

</form>

</div>

</div>

</x-app-layout>