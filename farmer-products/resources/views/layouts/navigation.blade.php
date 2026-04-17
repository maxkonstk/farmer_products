<header class="site-header">
    <div class="site-container site-header__inner">
        <a href="{{ route('home') }}" class="brand">
            <span class="brand__mark">Ф</span>
            <span>
                <span class="brand__title">Фермерская лавка</span>
                <span class="brand__subtitle">личный кабинет пользователя</span>
            </span>
        </a>

        <nav class="site-nav">
            <a href="{{ route('home') }}" class="site-nav__link">Магазин</a>
            <a href="{{ route('dashboard') }}" class="site-nav__link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}">Кабинет</a>
            <a href="{{ route('account.orders.index') }}" class="site-nav__link {{ request()->routeIs('account.orders.*') ? 'is-active' : '' }}">Заказы</a>
            <a href="{{ route('profile.edit') }}" class="site-nav__link {{ request()->routeIs('profile.*') ? 'is-active' : '' }}">Профиль</a>
            @if (auth()->user()->is_admin)
                <a href="{{ route('admin.dashboard') }}" class="site-nav__link">Админ-панель</a>
            @endif
        </nav>

        <div class="site-actions">
            <a href="{{ route('cart.index') }}" class="cart-button">
                <span>Корзина</span>
                <span class="cart-button__count">{{ $cartItemsCount }}</span>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-ghost">Выйти</button>
            </form>
        </div>
    </div>
</header>
