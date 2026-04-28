<nav class="account-nav">
    <a href="{{ route('dashboard') }}" class="account-nav__link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}">Обзор</a>
    <a href="{{ route('account.orders.index') }}" class="account-nav__link {{ request()->routeIs('account.orders.*') ? 'is-active' : '' }}">Заказы</a>
    <a href="{{ route('account.favorites.index') }}" class="account-nav__link {{ request()->routeIs('account.favorites.*') ? 'is-active' : '' }}">Избранное</a>
    <a href="{{ route('account.addresses.index') }}" class="account-nav__link {{ request()->routeIs('account.addresses.*') ? 'is-active' : '' }}">Адреса</a>
    <a href="{{ route('profile.edit') }}" class="account-nav__link {{ request()->routeIs('profile.*') ? 'is-active' : '' }}">Профиль</a>
</nav>
