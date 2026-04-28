@extends('layouts.site')

@section('title', 'Новый адрес')

@section('content')
    <section class="page-section">
        <div class="site-container">
            @include('account._nav')

            <div class="page-intro">
                <div>
                    <p class="eyebrow">Личный кабинет</p>
                    <h1 class="page-title">Добавить адрес</h1>
                    <p class="page-subtitle">Этот адрес можно будет подставлять на checkout одним кликом.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('account.addresses.store') }}" class="form-card">
                @include('account.addresses._form', ['submitLabel' => 'Сохранить адрес'])
            </form>
        </div>
    </section>
@endsection
