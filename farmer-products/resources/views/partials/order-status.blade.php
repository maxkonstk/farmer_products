@php
    $statusValue = $status instanceof \App\Enums\OrderStatus ? $status->value : (string) $status;
    $statusLabel = $status instanceof \App\Enums\OrderStatus ? $status->label() : \App\Enums\OrderStatus::from($statusValue)->label();
@endphp

<span class="status-badge status-badge--{{ $statusValue }}">
    {{ $statusLabel }}
</span>
