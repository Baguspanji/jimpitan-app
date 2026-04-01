<x-layouts::warga :title="__('Link Kedaluwarsa')">
    <div class="flex flex-col items-center justify-center min-h-[60vh] text-center px-4">
        <div class="bg-white rounded-xl border border-gray-200 p-8 max-w-sm w-full">
            <div class="w-14 h-14 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-orange-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z" />
                </svg>
            </div>
            <h1 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Link Kedaluwarsa') }}</h1>
            <p class="text-sm text-gray-500">
                {{ __('Link akses ini sudah tidak berlaku. Silakan hubungi admin untuk mendapatkan link baru.') }}
            </p>
        </div>
    </div>
</x-layouts::warga>
