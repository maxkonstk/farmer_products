@extends('layouts.admin')

@section('title', 'Новая коллекция')
@section('page-title', 'Создать коллекцию')
@section('page-subtitle', 'Сезонные подборки, стартовые корзины и тематические промо-наборы')

@section('content')
    <form method="POST" action="{{ route('admin.collections.store') }}" enctype="multipart/form-data" class="form-card">
        @csrf
        @include('admin.collections._form', ['submitLabel' => 'Создать коллекцию'])
    </form>
@endsection
