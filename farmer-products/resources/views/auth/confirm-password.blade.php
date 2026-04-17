<x-guest-layout>
    <div class="space-y-6">
        <div>
            <p class="eyebrow">Подтверждение действия</p>
            <h1 class="text-3xl font-black text-slate-900">Введите пароль</h1>
            <p class="mt-2 text-sm text-slate-600">Для доступа к защищенному разделу подтвердите текущий пароль.</p>
        </div>

        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
            @csrf

            <div>
                <x-input-label for="password" value="Пароль" />
                <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex justify-end">
                <x-primary-button>
                    Подтвердить
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
