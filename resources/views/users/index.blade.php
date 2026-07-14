<x-app-layout>

<x-slot name="header">

<div class="flex justify-between items-center">

    <div>

        <h2 class="text-3xl font-bold">

            👥 Потребители

        </h2>

        <p class="text-gray-500">

            Управление на потребителите

        </p>

    </div>

    <a
        href="{{ route('users.create') }}"
        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl">

        ➕ Нов потребител

    </a>

</div>

</x-slot>

@if(session('success'))

<div class="bg-green-100 border border-green-300 text-green-700 p-4 rounded-xl mb-6">

    {{ session('success') }}

</div>

@endif

@if(session('error'))

<div class="bg-red-100 border border-red-300 text-red-700 p-4 rounded-xl mb-6">

    {{ session('error') }}

</div>

@endif

<div class="bg-white rounded-2xl shadow-lg overflow-hidden">

<table class="w-full">

<thead class="bg-slate-100">

<tr>

<th class="text-left p-4">Име</th>

<th class="text-left p-4">Имейл</th>

<th class="text-left p-4">Роля</th>

<th class="text-right p-4">Действия</th>

</tr>

</thead>

<tbody>

@foreach($users as $user)

<tr class="border-t">

<td class="p-4 font-semibold">

{{ $user->name }}

</td>

<td class="p-4">

{{ $user->email }}

</td>

<td class="p-4">

@if($user->role=='admin')

<span class="bg-red-100 text-red-700 px-3 py-1 rounded-full">

👑 Admin

</span>

@elseif($user->role=='dispatcher')

<span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full">

📋 Dispatcher

</span>

@else

<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full">

🚛 Driver

</span>

@endif

</td>

<td class="p-4">

<div class="flex justify-end gap-2">

<a
href="{{ route('users.edit',$user) }}"
class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg">

✏️

</a>

<form
action="{{ route('users.destroy',$user) }}"
method="POST">

@csrf
@method('DELETE')

<button
onclick="return confirm('Сигурни ли сте?')"
class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">

🗑️

</button>

</form>

</div>

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

</x-app-layout>