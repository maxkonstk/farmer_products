@extends('layouts.admin')

@section('title', 'Товары')
@section('page-title', 'Товары')
@section('page-subtitle', 'Операционный каталог с поиском, фильтрами по витрине и сигналами по остаткам')
@section('page-note-title', 'Материалы каталога')
@section('page-note', 'Здесь удобно держать ассортимент в рабочем состоянии: скрывать позиции, отслеживать низкий остаток и быстро находить товары по поставщику, региону и подборкам.')

@section('content')
    <div class="toolbar toolbar--between admin-toolbar">
        <form method="GET" class="toolbar admin-toolbar__search">
            <div class="form-group admin-toolbar__field">
                <label for="q" class="form-label">Поиск товара</label>
                <input id="q" type="search" name="q" value="{{ $search }}" class="form-control" placeholder="Название, поставщик, регион или slug">
            </div>
            <div class="form-group admin-toolbar__field">
                <label for="category_id" class="form-label">Категория</label>
                <select id="category_id" name="category_id" class="form-control">
                    <option value="">Все категории</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected($categoryId === $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group admin-toolbar__field">
                <label for="visibility" class="form-label">Витрина</label>
                <select id="visibility" name="visibility" class="form-control">
                    <option value="">Все статусы</option>
                    <option value="active" @selected($visibility === 'active')>Показывается</option>
                    <option value="hidden" @selected($visibility === 'hidden')>Скрыт</option>
                </select>
            </div>
            <div class="form-group admin-toolbar__field">
                <label for="featured" class="form-label">Мерчандайзинг</label>
                <select id="featured" name="featured" class="form-control">
                    <option value="">Любой</option>
                    <option value="featured" @selected($featured === 'featured')>Хит / featured</option>
                    <option value="standard" @selected($featured === 'standard')>Без бейджа featured</option>
                </select>
            </div>
            <div class="form-group admin-toolbar__field">
                <label for="stock_state" class="form-label">Остаток</label>
                <select id="stock_state" name="stock_state" class="form-control">
                    <option value="">Любой</option>
                    <option value="in" @selected($stockState === 'in')>Нормальный</option>
                    <option value="low" @selected($stockState === 'low')>Низкий ≤ {{ $stockThreshold }}</option>
                    <option value="out" @selected($stockState === 'out')>Нет в наличии</option>
                </select>
            </div>
            <div class="filter-panel__actions admin-toolbar__actions">
                <button type="submit" class="btn btn-outline">Применить</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-ghost">Сбросить</a>
            </div>
        </form>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary admin-toolbar__button">Добавить товар</a>
    </div>

    <div class="table-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Товар</th>
                    <th>Категория</th>
                    <th>Цена</th>
                    <th>Остаток</th>
                    <th>Витрина</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    @php
                        $stockStateClass = match ($product->stockState()) {
                            'out' => 'status-badge--out-of-stock',
                            'low' => 'status-badge--low-stock',
                            default => 'status-badge--in-stock',
                        };
                        $stockStateLabel = match ($product->stockState()) {
                            'out' => 'Нет в наличии',
                            'low' => 'Низкий остаток',
                            default => 'В наличии',
                        };
                    @endphp
                    <tr>
                        <td>
                            <strong>{{ $product->name }}</strong>
                            <span class="table-cell__meta">
                                {{ $product->producer_name ?: 'Поставщик не указан' }}
                                @if ($product->origin_location)
                                    · {{ $product->origin_location }}
                                @endif
                            </span>
                        </td>
                        <td>
                            {{ $product->category->name }}
                            <span class="table-cell__meta">
                                Подборок: {{ $product->collections_count }} · В избранном: {{ $product->favorited_by_users_count }}
                            </span>
                        </td>
                        <td>
                            {{ number_format((float) $product->price, 0, ',', ' ') }} ₽
                            <span class="table-cell__meta">{{ $product->weight ?: 'Вес не указан' }}</span>
                        </td>
                        <td>
                            <span class="status-badge {{ $stockStateClass }}">{{ $stockStateLabel }}</span>
                            <span class="table-cell__meta">{{ $product->stock }} шт. на складе</span>
                        </td>
                        <td>
                            <span class="status-badge {{ $product->is_active ? 'status-badge--completed' : 'status-badge--cancelled' }}">
                                {{ $product->is_active ? 'Показывается' : 'Скрыт' }}
                            </span>
                            <span class="table-cell__meta">
                                {{ $product->is_featured ? 'Выделен на витрине' : 'Обычная карточка каталога' }}
                            </span>
                        </td>
                        <td class="table-actions">
                            <a href="{{ route('admin.products.edit', $product) }}" class="table-link">Редактировать</a>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Удалить товар?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-ghost">Удалить</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">По текущим фильтрам товары не найдены.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrap">
        {{ $products->links() }}
    </div>
@endsection
