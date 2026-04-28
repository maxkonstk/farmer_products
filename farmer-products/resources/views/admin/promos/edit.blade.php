@extends('layouts.admin')

@section('title', 'Редактировать промо')
@section('page-title', 'Редактировать промо-блок')
@section('page-subtitle', 'Обновите расписание, оффер и CTA без правки витрины')

@section('content')
    <form method="POST" action="{{ route('admin.promos.update', $promo) }}" enctype="multipart/form-data" class="form-card">
        @csrf
        @method('PUT')
        @include('admin.promos._form', ['submitLabel' => 'Сохранить изменения'])
    </form>
@endsection
