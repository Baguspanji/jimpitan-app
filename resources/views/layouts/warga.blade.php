<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <title>
            {{ filled($title ?? null) ? $title.' - '.config('app.name', 'Laravel') : config('app.name', 'Laravel') }}
        </title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-gray-50 font-sans antialiased">
        <header class="bg-white border-b border-gray-200 px-4 py-3">
            <div class="max-w-lg mx-auto">
                <p class="text-sm font-semibold text-gray-900">{{ config('app.name', 'SiJimbar') }}</p>
                <p class="text-xs text-gray-500">{{ __('Ringkasan Jimpitan Peserta') }}</p>
            </div>
        </header>

        <main class="max-w-lg mx-auto px-4 py-6">
            {{ $slot }}
        </main>
    </body>
</html>
