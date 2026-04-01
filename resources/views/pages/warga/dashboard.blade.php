<x-layouts::warga :title="$participant->name">
    {{-- Identity card --}}
    <div class="bg-white rounded-2xl border border-emerald-100 shadow-sm p-4 mb-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-base font-bold text-gray-900">{{ $participant->name }}</h1>
                <p class="text-sm text-gray-500">{{ $maskedPhone }}</p>
                @if ($participant->address)
                    <p class="text-xs text-gray-400">{{ $participant->address }}</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Orders --}}
    @forelse ($participant->orders as $order)
        @php
            $paidCount = $order->installments->count();
            $weeklyTotal = $order->weeklyTotal();
            $totalOrder = 45 * $weeklyTotal;
            $totalPaid = $order->installments->sum('amount_paid');
            $remaining = $totalOrder - $totalPaid;
            $progressPct = $totalOrder > 0 ? min(100, round($paidCount / 45 * 100)) : 0;
        @endphp

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-4">
            <h2 class="font-semibold text-emerald-700 mb-3">{{ __('Periode :name', ['name' => $order->period_name]) }}</h2>

            {{-- Items --}}
            <div class="mb-3 divide-y divide-gray-100">
                @foreach ($order->orderItems as $orderItem)
                    <div class="flex justify-between py-1.5 text-sm">
                        <span class="text-gray-700">{{ $orderItem->item->name }} ×{{ $orderItem->qty }}/{{ $orderItem->item->unit }}</span>
                        <span class="text-gray-900 font-medium">
                            Rp {{ number_format($orderItem->item->weekly_price * $orderItem->qty, 0, ',', '.') }}/minggu
                        </span>
                    </div>
                @endforeach
            </div>

            {{-- Progress bar --}}
            <div class="mb-2">
                <div class="flex justify-between text-xs text-gray-500 mb-1">
                    <span>{{ $paidCount }}/45 {{ __('minggu') }}</span>
                    <span>{{ $progressPct }}%</span>
                </div>
                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-emerald-500 rounded-full transition-all" style="width: {{ $progressPct }}%"></div>
                </div>
            </div>

            {{-- Week dots --}}
            <div class="flex flex-wrap gap-1 py-2">
                @for ($w = 1; $w <= 45; $w++)
                    @php $paid = $order->installments->contains('week_number', $w); @endphp
                    <div
                        class="w-4 h-4 rounded-sm {{ $paid ? 'bg-emerald-500' : 'bg-gray-100' }}"
                        title="{{ __('Minggu :n', ['n' => $w]) }}"
                    ></div>
                @endfor
            </div>

            {{-- Financial summary --}}
            <div class="mt-3 grid grid-cols-3 gap-2 text-center">
                <div class="bg-gray-50 rounded-xl p-2">
                    <p class="text-xs text-gray-400">{{ __('Total') }}</p>
                    <p class="text-sm font-bold text-gray-700">Rp {{ number_format($totalOrder, 0, ',', '.') }}</p>
                </div>
                <div class="bg-emerald-50 rounded-xl p-2">
                    <p class="text-xs text-gray-400">{{ __('Dibayar') }}</p>
                    <p class="text-sm font-bold text-emerald-700">Rp {{ number_format($totalPaid, 0, ',', '.') }}</p>
                </div>
                <div class="bg-amber-50 rounded-xl p-2">
                    <p class="text-xs text-gray-400">{{ __('Sisa') }}</p>
                    <p class="text-sm font-bold text-amber-700">Rp {{ number_format($remaining, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-4 text-center text-gray-400 text-sm">
            {{ __('Belum ada data order.') }}
        </div>
    @endforelse

    {{-- Savings --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-4">
        <h2 class="font-semibold text-emerald-700 mb-3">{{ __('Tabungan') }}</h2>

        @if ($participant->savingRecord)
            <div class="bg-emerald-50 rounded-xl p-3 mb-3 text-center">
                <p class="text-xs text-gray-400">{{ __('Saldo') }}</p>
                <p class="text-xl font-bold text-emerald-700">
                    Rp {{ number_format($participant->savingRecord->balance, 0, ',', '.') }}
                </p>
            </div>
        @endif

        @if ($participant->savingTransactions->isNotEmpty())
            <p class="text-xs text-gray-400 mb-2">{{ __('5 Transaksi Terakhir') }}</p>
            <div class="divide-y divide-gray-100">
                @foreach ($participant->savingTransactions as $tx)
                    <div class="flex justify-between py-1.5 text-sm">
                        <div>
                            <span class="{{ $tx->type === 'debit' ? 'text-red-600' : 'text-green-600' }} font-medium">
                                {{ $tx->type === 'debit' ? '-' : '+' }}
                                Rp {{ number_format($tx->amount, 0, ',', '.') }}
                            </span>
                            @if ($tx->note)
                                <p class="text-xs text-gray-400">{{ $tx->note }}</p>
                            @endif
                        </div>
                        <span class="text-gray-400 text-xs">{{ $tx->transaction_date->format('d M Y') }}</span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-400">{{ __('Belum ada transaksi.') }}</p>
        @endif
    </div>

    {{-- Footer --}}
    <div class="mt-6 bg-amber-50 border border-amber-100 rounded-xl px-4 py-2.5 text-center">
        <p class="text-xs text-amber-700">
            🔒 {{ __('Link berlaku sampai') }} {{ $expiresAt->translatedFormat('d F Y, H:i') }}
        </p>
    </div>
</x-layouts::warga>
