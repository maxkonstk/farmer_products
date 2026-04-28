@extends('layouts.admin')

@section('title', 'Редактирование хозяйства')
@section('page-title', 'Редактировать хозяйство')
@section('page-subtitle', 'Обновление публичной карточки поставщика')

@section('content')
    <form method="POST" action="{{ route('admin.farmers.update', $farmer) }}" enctype="multipart/form-data" class="form-card">
        @csrf
        @method('PATCH')
        @include('admin.farmers._form', ['submitLabel' => 'Сохранить изменения'])
    </form>
@endsection
