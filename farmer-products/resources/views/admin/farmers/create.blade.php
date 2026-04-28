@extends('layouts.admin')

@section('title', 'Новое хозяйство')
@section('page-title', 'Добавить хозяйство')
@section('page-subtitle', 'Карточка поставщика для главной страницы и раздела «О нас»')

@section('content')
    <form method="POST" action="{{ route('admin.farmers.store') }}" enctype="multipart/form-data" class="form-card">
        @csrf
        @include('admin.farmers._form', ['submitLabel' => 'Сохранить', 'farmer' => null])
    </form>
@endsection
