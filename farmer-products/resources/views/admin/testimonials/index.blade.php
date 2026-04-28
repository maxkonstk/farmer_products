@extends('layouts.admin')

@section('title', 'Отзывы')
@section('page-title', 'Отзывы покупателей')
@section('page-subtitle', 'Социальное доказательство для главной страницы и trust-блоков')

@section('content')
    <div class="toolbar admin-toolbar admin-toolbar--actions-only">
        <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary">Добавить отзыв</a>
    </div>

    <div class="table-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Автор</th>
                    <th>Контекст</th>
                    <th>Цитата</th>
                    <th>Статус</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($testimonials as $testimonial)
                    <tr>
                        <td>{{ $testimonial->author }}</td>
                        <td>{{ $testimonial->role }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($testimonial->quote, 90) }}</td>
                        <td>{{ $testimonial->is_published ? 'Опубликовано' : 'Черновик' }}</td>
                        <td class="table-actions">
                            <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="table-link">Редактировать</a>
                            <form method="POST" action="{{ route('admin.testimonials.destroy', $testimonial) }}" onsubmit="return confirm('Удалить отзыв?');">
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
        {{ $testimonials->links() }}
    </div>
@endsection
