<?php

use App\Models\Installment;
use App\Models\Item;
use App\Models\Order;
use App\Models\Participant;
use App\Models\Saving;
use App\Models\SavingTransaction;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Dashboard')] class extends Component {
    #[Computed]
    public function totalParticipants(): int
    {
        return Participant::count();
    }

    #[Computed]
    public function totalOrders(): int
    {
        return Order::count();
    }

    #[Computed]
    public function totalInstallmentsCollected(): int
    {
        return (int) Installment::sum('amount_paid');
    }

    #[Computed]
    public function totalSavingsBalance(): int
    {
        return (int) Saving::sum('balance');
    }

    #[Computed]
    public function totalItems(): int
    {
        return Item::count();
    }

    #[Computed]
    public function recentInstallments(): \Illuminate\Database\Eloquent\Collection
    {
        return Installment::with(['order.participant'])
            ->orderByDesc('payment_date')
            ->orderByDesc('id')
            ->limit(6)
            ->get();
    }

    #[Computed]
    public function recentSavingTransactions(): \Illuminate\Database\Eloquent\Collection
    {
        return SavingTransaction::with('participant')
            ->orderByDesc('transaction_date')
            ->orderByDesc('id')
            ->limit(6)
            ->get();
    }

    #[Computed]
    public function totalInstallmentsPaid(): int
    {
        return Installment::count();
    }

    #[Computed]
    public function totalWeeksExpected(): int
    {
        return Order::count() * 45;
    }
}
?>

<div class="flex flex-col gap-6">

    <flux:heading size="xl">{{ __('Dashboard') }}</flux:heading>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        <a href="{{ route('participants.index') }}" wire:navigate class="block">
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-5 hover:border-zinc-400 dark:hover:border-zinc-500 transition-colors">
                <div class="flex items-center justify-between mb-3">
                    <flux:text size="sm" class="text-zinc-400">{{ __('Total Peserta') }}</flux:text>
                    <div class="p-2 rounded-lg bg-blue-50 dark:bg-blue-900/30">
                        <flux:icon.users class="size-5 text-blue-500" />
                    </div>
                </div>
                <div class="text-3xl font-bold text-zinc-800 dark:text-zinc-100">
                    {{ number_format($this->totalParticipants) }}
                </div>
                <flux:text size="sm" class="text-zinc-400 mt-1">{{ __('Warga terdaftar') }}</flux:text>
            </div>
        </a>

        <a href="{{ route('orders.index') }}" wire:navigate class="block">
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-5 hover:border-zinc-400 dark:hover:border-zinc-500 transition-colors">
                <div class="flex items-center justify-between mb-3">
                    <flux:text size="sm" class="text-zinc-400">{{ __('Total Order') }}</flux:text>
                    <div class="p-2 rounded-lg bg-violet-50 dark:bg-violet-900/30">
                        <flux:icon.clipboard-document-list class="size-5 text-violet-500" />
                    </div>
                </div>
                <div class="text-3xl font-bold text-zinc-800 dark:text-zinc-100">
                    {{ number_format($this->totalOrders) }}
                </div>
                <flux:text size="sm" class="text-zinc-400 mt-1">{{ __('Peserta aktif') }}</flux:text>
            </div>
        </a>

        <a href="{{ route('installments.index') }}" wire:navigate class="block">
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-5 hover:border-zinc-400 dark:hover:border-zinc-500 transition-colors">
                <div class="flex items-center justify-between mb-3">
                    <flux:text size="sm" class="text-zinc-400">{{ __('Angsuran Terkumpul') }}</flux:text>
                    <div class="p-2 rounded-lg bg-green-50 dark:bg-green-900/30">
                        <flux:icon.currency-dollar class="size-5 text-green-500" />
                    </div>
                </div>
                <div class="text-2xl font-bold text-zinc-800 dark:text-zinc-100">
                    Rp {{ number_format($this->totalInstallmentsCollected, 0, ',', '.') }}
                </div>
                @if ($this->totalWeeksExpected > 0)
                    @php $progress = round($this->totalInstallmentsPaid / $this->totalWeeksExpected * 100); @endphp
                    <flux:text size="sm" class="text-zinc-400 mt-1">
                        {{ $this->totalInstallmentsPaid }} / {{ $this->totalWeeksExpected }} minggu ({{ $progress }}%)
                    </flux:text>
                @else
                    <flux:text size="sm" class="text-zinc-400 mt-1">{{ __('Belum ada angsuran') }}</flux:text>
                @endif
            </div>
        </a>

        <a href="{{ route('savings.index') }}" wire:navigate class="block">
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-5 hover:border-zinc-400 dark:hover:border-zinc-500 transition-colors">
                <div class="flex items-center justify-between mb-3">
                    <flux:text size="sm" class="text-zinc-400">{{ __('Total Saldo Tabungan') }}</flux:text>
                    <div class="p-2 rounded-lg bg-amber-50 dark:bg-amber-900/30">
                        <flux:icon.banknotes class="size-5 text-amber-500" />
                    </div>
                </div>
                <div class="text-2xl font-bold text-zinc-800 dark:text-zinc-100">
                    Rp {{ number_format($this->totalSavingsBalance, 0, ',', '.') }}
                </div>
                <flux:text size="sm" class="text-zinc-400 mt-1">{{ __('Saldo gabungan peserta') }}</flux:text>
            </div>
        </a>

    </div>

    {{-- Quick Links --}}
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
        <a href="{{ route('participants.index') }}" wire:navigate
           class="flex flex-col items-center gap-2 p-4 rounded-xl border border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors text-sm text-zinc-600 dark:text-zinc-300 text-center">
            <flux:icon.users class="size-6 text-zinc-400" />{{ __('Peserta') }}
        </a>
        <a href="{{ route('categories.index') }}" wire:navigate
           class="flex flex-col items-center gap-2 p-4 rounded-xl border border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors text-sm text-zinc-600 dark:text-zinc-300 text-center">
            <flux:icon.tag class="size-6 text-zinc-400" />{{ __('Kategori') }}
        </a>
        <a href="{{ route('items.index') }}" wire:navigate
           class="flex flex-col items-center gap-2 p-4 rounded-xl border border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors text-sm text-zinc-600 dark:text-zinc-300 text-center">
            <flux:icon.shopping-bag class="size-6 text-zinc-400" />{{ __('Barang') }}
        </a>
        <a href="{{ route('orders.index') }}" wire:navigate
           class="flex flex-col items-center gap-2 p-4 rounded-xl border border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors text-sm text-zinc-600 dark:text-zinc-300 text-center">
            <flux:icon.clipboard-document-list class="size-6 text-zinc-400" />{{ __('Order') }}
        </a>
        <a href="{{ route('installments.index') }}" wire:navigate
           class="flex flex-col items-center gap-2 p-4 rounded-xl border border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors text-sm text-zinc-600 dark:text-zinc-300 text-center">
            <flux:icon.calendar-days class="size-6 text-zinc-400" />{{ __('Angsuran') }}
        </a>
    </div>

    {{-- Recent Activity (Two-column) --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Recent Installments --}}
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 overflow-hidden">
            <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
                <flux:heading size="lg">{{ __('Angsuran Terbaru') }}</flux:heading>
                <flux:button variant="ghost" size="sm" :href="route('installments.index')" wire:navigate>
                    {{ __('Lihat semua') }}
                </flux:button>
            </div>
            <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @forelse ($this->recentInstallments as $installment)
                    <div class="px-5 py-3 flex items-center justify-between">
                        <div>
                            <flux:text class="font-medium">{{ $installment->order->participant->name }}</flux:text>
                            <flux:text size="sm" class="text-zinc-400">
                                Minggu {{ $installment->week_number }}
                                &middot; {{ \Carbon\Carbon::parse($installment->payment_date)->format('d/m/Y') }}
                            </flux:text>
                        </div>
                        <flux:badge color="green" size="sm">
                            Rp {{ number_format($installment->amount_paid, 0, ',', '.') }}
                        </flux:badge>
                    </div>
                @empty
                    <div class="px-5 py-8 text-center text-zinc-400">
                        {{ __('Belum ada angsuran tercatat.') }}
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Recent Saving Transactions --}}
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 overflow-hidden">
            <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
                <flux:heading size="lg">{{ __('Transaksi Tabungan Terbaru') }}</flux:heading>
                <flux:button variant="ghost" size="sm" :href="route('savings.index')" wire:navigate>
                    {{ __('Lihat semua') }}
                </flux:button>
            </div>
            <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @forelse ($this->recentSavingTransactions as $tx)
                    <div class="px-5 py-3 flex items-center justify-between">
                        <div>
                            <flux:text class="font-medium">{{ $tx->participant->name }}</flux:text>
                            <flux:text size="sm" class="text-zinc-400">
                                {{ $tx->type === 'deposit' ? 'Setoran' : 'Penarikan' }}
                                &middot; {{ \Carbon\Carbon::parse($tx->transaction_date)->format('d/m/Y') }}
                            </flux:text>
                        </div>
                        <flux:badge color="{{ $tx->type === 'deposit' ? 'green' : 'red' }}" size="sm">
                            {{ $tx->type === 'deposit' ? '+' : '-' }}
                            Rp {{ number_format($tx->amount, 0, ',', '.') }}
                        </flux:badge>
                    </div>
                @empty
                    <div class="px-5 py-8 text-center text-zinc-400">
                        {{ __('Belum ada transaksi tabungan.') }}
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</div>
