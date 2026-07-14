<x-app-layout>

    <x-slot name="header">

        <div class="flex justify-between items-center">

            <div>

                <h2 class="text-3xl font-bold">

                    👤 Потребител

                </h2>

                <p class="text-gray-500">

                    {{ $user->name }}

                </p>

            </div>

            <div class="flex gap-3">

                <a
                    href="{{ route('users.edit',$user) }}"
                    class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-xl">

                    ✏️ Редакция

                </a>

                <a
                    href="{{ route('users.index') }}"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-xl">

                    ← Назад

                </a>

            </div>

        </div>

    </x-slot>

    <div class="max-w-4xl mx-auto">

        <div class="bg-white rounded-2xl shadow-lg p-8">

            <div class="grid md:grid-cols-2 gap-6">

                <div class="border-b pb-3">

                    <div class="text-gray-500">

                        Име

                    </div>

                    <div class="text-xl font-bold">

                        {{ $user->name }}

                    </div>

                </div>

                <div class="border-b pb-3">

                    <div class="text-gray-500">

                        Email

                    </div>

                    <div class="text-xl font-bold">

                        {{ $user->email }}

                    </div>

                </div>

                <div class="border-b pb-3">

                    <div class="text-gray-500">

                        Роля

                    </div>

                    <div class="text-xl font-bold">

                        @if($user->role=='admin')

                            👑 Administrator

                        @elseif($user->role=='dispatcher')

                            👨‍💼 Dispatcher

                        @else

                            🚛 Driver

                        @endif

                    </div>

                </div>

                <div class="border-b pb-3">

                    <div class="text-gray-500">

                        Регистрация

                    </div>

                    <div class="text-xl font-bold">

                        {{ $user->created_at->format('d.m.Y H:i') }}

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>