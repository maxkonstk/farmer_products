<x-guest-layout>
    <div class="space-y-6">
        <div>
            <p class="eyebrow">Авторизация</p>
            <h1 class="text-3xl font-black text-slate-900">Вход в систему</h1>
            <p class="mt-2 text-sm text-slate-600">Войдите, чтобы просматривать историю заказов и работать в административной панели.</p>
        </div>

        <x-auth-session-status class="text-sm text-green-700" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password" value="Пароль" />
                <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <label class="inline-flex items-center gap-2 text-sm text-slate-600">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300" name="remember">
                <span>Запомнить меня</span>
            </label>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                @if (Route::has('password.request'))
                    <a class="text-sm font-semibold text-green-800 underline" href="{{ route('password.request') }}">
                        Забыли пароль?
                    </a>
                @endif

                <x-primary-button>
                    Войти
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
