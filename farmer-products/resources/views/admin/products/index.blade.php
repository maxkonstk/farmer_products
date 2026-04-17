@extends('layouts.admin')

@section('title', 'Товары')
@section('page-title', 'Товары')
@section('page-subtitle', 'Список товаров с быстрым поиском по названию')
@section('page-note-title', 'Материалы каталога')
@section('page-note', 'Карточки товаров, остатки и статусы редактируются в одном месте, но интерфейс остаётся частью общей витрины, а не отдельной backend-системой.')

@section('content')
    <div class="toolbar toolbar--between admin-toolbar">
        <form method="GET" class="toolbar-search admin-toolbar__search">
            <div class="form-group admin-toolbar__field">
                <label for="q" class="form-label">Поиск товара</label>
                <input id="q" type="text" name="q" value="{{ $search }}" class="form-control" placeholder="Например, молоко, мед, хлеб">
            </div>
            <button type="submit" class="btn btn-outline">Найти</button>
        </form>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Добавить товар</a>
    </div>

    <div class="table-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Товар</th>
                    <th>Категория</th>
                    <th>Цена</th>
                    <th>Остаток</th>
                    <th>Статус</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category->name }}</td>
                        <td>{{ number_format((float) $product->price, 0, ',', ' ') }} ₽</td>
                        <td>{{ $product->stock }}</td>
                        <td>{{ $product->is_active ? 'Активен' : 'Скрыт' }}</td>
                        <td class="table-actions">
                            <a href="{{ route('admin.products.edit', $product) }}" class="table-link">Редактировать</a>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Удалить товар?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-ghost">Удалить</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pagination-wrap">
        {{ $products->links() }}
    </div>
@endsection
