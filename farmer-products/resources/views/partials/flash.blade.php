@php
    $flashMessages = [
        'success' => 'flash--success',
        'error' => 'flash--error',
        'warning' => 'flash--warning',
    ];
@endphp

@if ($errors->any())
    <div class="site-container">
        <div class="flash flash--error">
            <div class="flash__title">Проверьте данные формы</div>
            <ul class="flash__list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

@foreach ($flashMessages as $key => $class)
    @if (session($key))
        <div class="site-container">
            <div class="flash {{ $class }}">{{ session($key) }}</div>
        </div>
    @endif
@endforeach
