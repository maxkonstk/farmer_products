@extends('layouts.site')

@section('title', 'Профиль')

@section('content')
    <section class="page-section">
        <div class="site-container">
            @include('account._nav')

            <div class="page-intro">
                <div>
                    <p class="eyebrow">Личный кабинет</p>
                    <h1 class="page-title">Профиль пользователя</h1>
                    <p class="page-subtitle">Обновите контакты, смените пароль и управляйте доступом к учетной записи.</p>
                </div>
            </div>

            <div class="account-settings-grid">
                <div class="content-card">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <div class="content-card">
                    @include('profile.partials.update-password-form')
                </div>

                <div class="content-card">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </section>
@endsection
