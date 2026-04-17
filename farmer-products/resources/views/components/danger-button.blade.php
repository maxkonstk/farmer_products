<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn']) }} style="background:#a64f3f;color:#fff;">
    {{ $slot }}
</button>
