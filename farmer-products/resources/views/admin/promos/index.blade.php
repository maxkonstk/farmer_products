@extends('layouts.admin')

@section('title', 'Промо')
@section('page-title', 'Промо-блоки и merchandising')
@section('page-subtitle', 'Управляйте акцентами на главной и в каталоге без правки шаблонов')

@section('content')
    <div class="toolbar admin-toolbar">
        <form method="GET" class="search-form">
            <input type="text" name="q" value="{{ $search }}" class="form-control" placeholder="Поиск по названию или заголовку промо">
            <button type="submit" class="btn btn-outline">Найти</button>
        </form>
        <a href="{{ route('admin.promos.create') }}" class="btn btn-primary">Новый промо-блок</a>
    </div>

    <div class="table-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Промо</th>
                    <th>Размещение</th>
                    <th>Привязка</th>
                    <th>Статус</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($promos as $promo)
                    <tr>
                        <td>
                            <strong>{{ $promo->name }}</strong>
                            <div class="table-note">{{ $promo->title }}</div>
                        </td>
                        <td>
                            {{ $promo->placement_label }}
                            <div class="table-note">{{ $promo->theme_label }}</div>
                        </td>
                        <td>{{ $promo->target_label }}</td>
                        <td>
                            {{ $promo->is_published ? 'Опубликовано' : 'Черновик' }}
                            @if ($promo->starts_at || $promo->ends_at)
                                <div class="table-note">
                                    {{ optional($promo->starts_at)->format('d.m H:i') ?: 'сейчас' }}
                                    —
                                    {{ optional($promo->ends_at)->format('d.m H:i') ?: 'без срока' }}
                                </div>
                            @endif
                        </td>
                        <td class="table-actions">
                            <a href="{{ $promo->resolved_url }}" class="table-link">Открыть</a>
                            <a href="{{ route('admin.promos.edit', $promo) }}" class="table-link">Редактировать</a>
                            <form method="POST" action="{{ route('admin.promos.destroy', $promo) }}" onsubmit="return confirm('Удалить промо-блок?');">
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
        {{ $promos->links() }}
    </div>
@endsection
