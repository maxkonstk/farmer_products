<x-guest-layout>
    <div class="space-y-6">
        <div>
            <p class="eyebrow">Регистрация</p>
            <h1 class="text-3xl font-black text-slate-900">Создание учетной записи</h1>
            <p class="mt-2 text-sm text-slate-600">После регистрации пользователь сможет сохранять историю своих заказов.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <x-input-label for="name" value="Имя" />
                <x-text-input id="name" class="mt-1 block w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password" value="Пароль" />
                <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password_confirmation" value="Подтверждение пароля" />
                <x-text-input id="password_confirmation" class="mt-1 block w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <a class="text-sm font-semibold text-green-800 underline" href="{{ route('login') }}">
                    Уже зарегистрированы?
                </a>

                <x-primary-button>
                    Зарегистрироваться
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
