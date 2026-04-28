@extends('layouts.admin')

@section('title', 'Редактирование отзыва')
@section('page-title', 'Редактировать отзыв')
@section('page-subtitle', 'Обновление социального proof-блока на сайте')

@section('content')
    <form method="POST" action="{{ route('admin.testimonials.update', $testimonial) }}" class="form-card">
        @csrf
        @method('PATCH')
        @include('admin.testimonials._form', ['submitLabel' => 'Сохранить изменения'])
    </form>
@endsection
