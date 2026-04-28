@extends('layouts.site')

@section('title', 'Изменить адрес')

@section('content')
    <section class="page-section">
        <div class="site-container">
            @include('account._nav')

            <div class="page-intro">
                <div>
                    <p class="eyebrow">Личный кабинет</p>
                    <h1 class="page-title">Изменить адрес</h1>
                    <p class="page-subtitle">Обновите данные доставки и настройте адрес по умолчанию.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('account.addresses.update', $address) }}" class="form-card">
                @method('PATCH')
                @include('account.addresses._form', ['submitLabel' => 'Сохранить изменения'])
            </form>
        </div>
    </section>
@endsection
