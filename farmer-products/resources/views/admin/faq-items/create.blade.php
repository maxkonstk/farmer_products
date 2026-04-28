@extends('layouts.admin')

@section('title', 'Новый вопрос')
@section('page-title', 'Добавить вопрос')
@section('page-subtitle', 'Контент для FAQ и onboarding первого заказа')

@section('content')
    <form method="POST" action="{{ route('admin.faq-items.store') }}" class="form-card">
        @csrf
        @include('admin.faq-items._form', ['submitLabel' => 'Сохранить', 'faqItem' => null])
    </form>
@endsection
