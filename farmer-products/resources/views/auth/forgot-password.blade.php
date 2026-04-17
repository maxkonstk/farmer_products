<x-guest-layout>
    <div class="space-y-6">
        <div>
            <p class="eyebrow">Восстановление доступа</p>
            <h1 class="text-3xl font-black text-slate-900">Сброс пароля</h1>
            <p class="mt-2 text-sm text-slate-600">Введите email, и система отправит ссылку для восстановления пароля.</p>
        </div>

        <x-auth-session-status class="text-sm text-green-700" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

            <div>
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <x-primary-button>
                Отправить ссылку
            </x-primary-button>
        </form>
    </div>
</x-guest-layout>
