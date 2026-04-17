<?php

return [
    'order_number_prefix' => env('SHOP_ORDER_PREFIX', 'FL'),
    'navigation_cache_ttl' => (int) env('SHOP_NAVIGATION_CACHE_TTL', 3600),
    'support_email' => env('SHOP_SUPPORT_EMAIL', env('MAIL_FROM_ADDRESS', 'hello@example.com')),
];
