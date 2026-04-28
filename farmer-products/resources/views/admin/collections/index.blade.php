@extends('layouts.admin')

@section('title', 'Коллекции')
@section('page-title', 'Сезонные коллекции и подборки')
@section('page-subtitle', 'Управляйте curated blocks на главной и merchandising-структурой каталога')

@section('content')
    <div class="toolbar admin-toolbar">
        <form method="GET" class="search-form">
            <input type="text" name="q" value="{{ $search }}" class="form-control" placeholder="Поиск по названию коллекции">
            <button type="submit" class="btn btn-outline">Найти</button>
        </form>
        <a href="{{ route('admin.collections.create') }}" class="btn btn-primary">Новая коллекция</a>
    </div>

    <div class="table-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Название</th>
                    <th>Товары</th>
                    <th>Публикация</th>
                    <th>Главная</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($collections as $collection)
                    <tr>
                        <td>
                            <strong>{{ $collection->name }}</strong>
                            @if ($collection->badge)
                                <div class="table-note">{{ $collection->badge }}</div>
                            @endif
                        </td>
                        <td>{{ $collection->products_count }}</td>
                        <td>{{ $collection->is_published ? 'Опубликовано' : 'Черновик' }}</td>
                        <td>{{ $collection->is_featured ? 'Да' : 'Нет' }}</td>
                        <td class="table-actions">
                            <a href="{{ route('collections.show', $collection) }}" class="table-link">Открыть</a>
                            <a href="{{ route('admin.collections.edit', $collection) }}" class="table-link">Редактировать</a>
                            <form method="POST" action="{{ route('admin.collections.destroy', $collection) }}" onsubmit="return confirm('Удалить коллекцию?');">
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
        {{ $collections->links() }}
    </div>
@endsection
