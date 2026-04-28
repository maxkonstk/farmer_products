@php
    $flashMessages = [
        'success' => 'flash--success',
        'error' => 'flash--error',
        'warning' => 'flash--warning',
    ];
@endphp

@if ($errors->any())
    <div class="site-container">
        <div class="flash flash--error" role="alert" aria-live="assertive">
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
            <div class="flash {{ $class }}" @if ($key === 'error') role="alert" aria-live="assertive" @else role="status" aria-live="polite" @endif>{{ session($key) }}</div>
        </div>
    @endif
@endforeach
