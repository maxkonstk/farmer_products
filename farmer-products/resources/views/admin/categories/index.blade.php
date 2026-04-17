@extends('layouts.admin')

@section('title', 'Категории')
@section('page-title', 'Категории товаров')
@section('page-subtitle', 'Управление разделами каталога интернет-магазина')
@section('page-note-title', 'Структура витрины')
@section('page-note', 'Категории оформляют редакционные разделы магазина, поэтому в панели они подаются так же спокойно и читаемо, как на публичной части сайта.')

@section('content')
    <div class="toolbar admin-toolbar admin-toolbar--actions-only">
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Добавить категорию</a>
    </div>

    <div class="table-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Название</th>
                    <th>Slug</th>
                    <th>Товаров</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->slug }}</td>
                        <td>{{ $category->products_count }}</td>
                        <td class="table-actions">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="table-link">Редактировать</a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Удалить категорию?');">
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
        {{ $categories->links() }}
    </div>
@endsection
