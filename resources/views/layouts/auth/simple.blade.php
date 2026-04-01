<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen antialiased bg-gradient-to-br from-emerald-50 via-white to-amber-50 font-sans text-gray-800">

        {{-- Decorative blobs --}}
        <div class="fixed -top-24 -left-24 w-96 h-96 bg-emerald-200 rounded-full opacity-30 blur-3xl pointer-events-none"></div>
        <div class="fixed -bottom-24 -right-24 w-96 h-96 bg-amber-200 rounded-full opacity-30 blur-3xl pointer-events-none"></div>

        {{-- Back to home navbar --}}
        <div class="relative z-10 px-6 pt-5 flex justify-between items-center max-w-6xl mx-auto">
            <a href="{{ route('home') }}" class="flex items-center gap-2" wire:navigate>
                <img src="/assets/logo-square.png" alt="{{ config('app.name', 'SiJimbar') }}" class="h-9 w-9 object-contain">
                <span class="font-bold text-lg text-emerald-700">{{ config('app.name', 'SiJimbar') }}</span>
            </a>
            <a href="{{ route('home') }}" class="text-sm text-gray-500 hover:text-emerald-600 transition-colors flex items-center gap-1" wire:navigate>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Beranda
            </a>
        </div>

        {{-- Content --}}
        <div class="relative z-10 flex min-h-[calc(100vh-80px)] flex-col items-center justify-center gap-6 p-6 md:p-10">
            <div class="flex w-full max-w-sm flex-col gap-6">

                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2" wire:navigate>
                    <img src="/assets/logo-square.png" alt="{{ config('app.name', 'SiJimbar') }}" class="h-16 w-16 object-contain drop-shadow-md">
                    <span class="sr-only">{{ config('app.name', 'SiJimbar') }}</span>
                </a>

                {{-- Card --}}
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 px-8 py-8">
                    {{ $slot }}
                </div>

            </div>
        </div>

        @fluxScripts
    </body>
</html>
