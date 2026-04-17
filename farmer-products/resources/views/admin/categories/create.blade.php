@extends('layouts.admin')

@section('title', 'Новая категория')
@section('page-title', 'Создание категории')
@section('page-subtitle', 'Добавление нового раздела каталога')

@section('content')
    <div class="form-card">
        <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
            @csrf
            @include('admin.categories._form', ['submitLabel' => 'Сохранить категорию'])
        </form>
    </div>
@endsection
