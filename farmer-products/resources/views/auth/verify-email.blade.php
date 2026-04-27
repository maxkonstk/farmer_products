<x-guest-layout>
    <div class="space-y-6">
        <div>
            <p class="eyebrow">Подтверждение email</p>
            <h1 class="text-3xl font-black text-slate-900">Подтвердите адрес электронной почты</h1>
            <p class="mt-2 text-sm text-slate-600">Мы отправили письмо со ссылкой подтверждения. После подтверждения откроется история заказов и административные разделы.</p>
        </div>

        @if (session('status') === 'verification-link-sent')
            <div class="rounded-3xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                Новая ссылка подтверждения отправлена на указанный адрес.
            </div>
        @endif

        <div class="space-y-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <x-primary-button>
                    Отправить ссылку повторно
                </x-primary-button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="text-sm font-semibold text-green-800 underline">
                    Выйти
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
