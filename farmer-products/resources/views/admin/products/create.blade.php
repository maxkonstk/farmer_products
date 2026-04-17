@extends('layouts.admin')

@section('title', 'Новый товар')
@section('page-title', 'Создание товара')
@section('page-subtitle', 'Добавление карточки нового продукта в каталог')

@section('content')
    <div class="form-card">
        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
            @csrf
            @include('admin.products._form', ['submitLabel' => 'Сохранить товар'])
        </form>
    </div>
@endsection
