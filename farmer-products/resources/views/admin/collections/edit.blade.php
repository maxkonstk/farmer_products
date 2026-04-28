@extends('layouts.admin')

@section('title', 'Редактирование коллекции')
@section('page-title', 'Редактировать коллекцию')
@section('page-subtitle', 'Обновите состав подборки, сроки показа и позиционирование на витрине')

@section('content')
    <form method="POST" action="{{ route('admin.collections.update', $collection) }}" enctype="multipart/form-data" class="form-card">
        @csrf
        @method('PUT')
        @include('admin.collections._form', ['submitLabel' => 'Сохранить изменения'])
    </form>
@endsection
