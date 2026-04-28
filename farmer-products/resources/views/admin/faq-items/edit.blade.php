@extends('layouts.admin')

@section('title', 'Редактирование вопроса')
@section('page-title', 'Редактировать вопрос')
@section('page-subtitle', 'Обновление FAQ на публичной части сайта')

@section('content')
    <form method="POST" action="{{ route('admin.faq-items.update', $faqItem) }}" class="form-card">
        @csrf
        @method('PATCH')
        @include('admin.faq-items._form', ['submitLabel' => 'Сохранить изменения'])
    </form>
@endsection
