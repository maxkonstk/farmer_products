@extends('layouts.admin')

@section('title', 'Редактирование товара')
@section('page-title', 'Редактирование товара')
@section('page-subtitle', 'Изменение данных карточки товара')

@section('content')
    <div class="form-card">
        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.products._form', ['submitLabel' => 'Обновить товар'])
        </form>
    </div>
@endsection
