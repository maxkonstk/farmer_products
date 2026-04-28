@extends('layouts.admin')

@section('title', 'Новый промо-блок')
@section('page-title', 'Новый промо-блок')
@section('page-subtitle', 'Создайте merchandising-блок для главной или каталога')

@section('content')
    <form method="POST" action="{{ route('admin.promos.store') }}" enctype="multipart/form-data" class="form-card">
        @csrf
        @include('admin.promos._form', ['submitLabel' => 'Сохранить', 'promo' => $promo])
    </form>
@endsection
