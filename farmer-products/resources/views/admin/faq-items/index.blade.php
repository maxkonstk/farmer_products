@extends('layouts.admin')

@section('title', 'FAQ')
@section('page-title', 'Частые вопросы')
@section('page-subtitle', 'База ответов для первой покупки и снижения ручных уточнений')

@section('content')
    <div class="toolbar admin-toolbar admin-toolbar--actions-only">
        <a href="{{ route('admin.faq-items.create') }}" class="btn btn-primary">Добавить вопрос</a>
    </div>

    <div class="table-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Вопрос</th>
                    <th>Ответ</th>
                    <th>Статус</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($faqItems as $faqItem)
                    <tr>
                        <td>{{ $faqItem->question }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($faqItem->answer, 110) }}</td>
                        <td>{{ $faqItem->is_published ? 'Опубликовано' : 'Черновик' }}</td>
                        <td class="table-actions">
                            <a href="{{ route('admin.faq-items.edit', $faqItem) }}" class="table-link">Редактировать</a>
                            <form method="POST" action="{{ route('admin.faq-items.destroy', $faqItem) }}" onsubmit="return confirm('Удалить вопрос?');">
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
        {{ $faqItems->links() }}
    </div>
@endsection
