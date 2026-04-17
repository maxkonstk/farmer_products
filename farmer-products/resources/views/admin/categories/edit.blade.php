@extends('layouts.admin')

@section('title', 'Редактирование категории')
@section('page-title', 'Редактирование категории')
@section('page-subtitle', 'Изменение названия, описания и изображения')

@section('content')
    <div class="form-card">
        <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.categories._form', ['submitLabel' => 'Обновить категорию'])
        </form>
    </div>
@endsection
