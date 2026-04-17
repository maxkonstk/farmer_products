<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="site-body">
        <div class="site-shell">
            @include('layouts.navigation')

            @include('partials.flash')

            @isset($header)
                <section class="page-section pb-0">
                    <div class="site-container">
                        {{ $header }}
                    </div>
                </section>
            @endisset

            <main class="site-main">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
