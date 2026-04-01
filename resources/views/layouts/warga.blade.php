<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <title>
            {{ filled($title ?? null) ? $title.' - '.config('app.name', 'SiJimbar') : config('app.name', 'SiJimbar') }}
        </title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen font-sans antialiased bg-gradient-to-b from-emerald-50 to-white">

        {{-- Fixed decorative blob --}}
        <div class="fixed top-0 right-0 w-64 h-64 bg-emerald-100 rounded-full opacity-40 blur-3xl pointer-events-none -translate-y-1/2 translate-x-1/2"></div>

        {{-- Header --}}
        <header class="sticky top-0 z-10 bg-white/80 backdrop-blur border-b border-gray-100">
            <div class="max-w-lg mx-auto px-4 py-3 flex items-center gap-3">
                <a href="{{ route('home') }}">
                    <img src="/assets/logo-square.png" alt="{{ config('app.name', 'SiJimbar') }}" class="h-8 w-8 object-contain">
                </a>
                <div>
                    <p class="text-sm font-bold text-emerald-700 leading-none">{{ config('app.name', 'SiJimbar') }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ __('Ringkasan Jimpitan Peserta') }}</p>
                </div>
            </div>
        </header>

        <main class="relative max-w-lg mx-auto px-4 py-6">
            {{ $slot }}
        </main>

        {{-- Footer --}}
        <footer class="text-center text-xs text-gray-300 pb-8 pt-2">
            © {{ date('Y') }} {{ config('app.name', 'SiJimbar') }}
        </footer>

    </body>
</html>
