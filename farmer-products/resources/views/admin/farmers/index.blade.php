@extends('layouts.admin')

@section('title', 'Фермеры')
@section('page-title', 'Фермеры и хозяйства')
@section('page-subtitle', 'Контентный слой доверия: поставщики, происхождение и специализация')

@section('content')
    <div class="toolbar admin-toolbar admin-toolbar--actions-only">
        <a href="{{ route('admin.farmers.create') }}" class="btn btn-primary">Добавить хозяйство</a>
    </div>

    <div class="table-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Название</th>
                    <th>Локация</th>
                    <th>Специализация</th>
                    <th>Статус</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($farmers as $farmer)
                    <tr>
                        <td>{{ $farmer->name }}</td>
                        <td>{{ $farmer->location }}</td>
                        <td>{{ $farmer->specialty }}</td>
                        <td>{{ $farmer->is_published ? 'Опубликовано' : 'Черновик' }}</td>
                        <td class="table-actions">
                            <a href="{{ route('admin.farmers.edit', $farmer) }}" class="table-link">Редактировать</a>
                            <form method="POST" action="{{ route('admin.farmers.destroy', $farmer) }}" onsubmit="return confirm('Удалить карточку хозяйства?');">
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
        {{ $farmers->links() }}
    </div>
@endsection
