@extends('layouts.admin')

@section('title', 'Новый отзыв')
@section('page-title', 'Добавить отзыв')
@section('page-subtitle', 'Управление блоком отзывов на публичной витрине')

@section('content')
    <form method="POST" action="{{ route('admin.testimonials.store') }}" class="form-card">
        @csrf
        @include('admin.testimonials._form', ['submitLabel' => 'Сохранить', 'testimonial' => null])
    </form>
@endsection
