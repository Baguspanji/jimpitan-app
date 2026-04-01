<?php

use App\Models\Installment;
use App\Models\Order;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

new #[Title('Laporan Keuangan')] class extends Component {
    #[Url]
    public string $filterPeriod = '';

    #[Computed]
    public function periods(): \Illuminate\Support\Collection
    {
        return Order::distinct()->orderByDesc('period_name')->pluck('period_name');
    }

    #[Computed]
    public function weeklyBreakdown(): \Illuminate\Support\Collection
    {
        return Installment::query()
            ->when($this->filterPeriod, fn ($q) => $q->whereHas('order', fn ($o) => $o->where('period_name', $this->filterPeriod)))
            ->selectRaw('week_number, SUM(amount_paid) as total, COUNT(*) as participants_count')
            ->groupBy('week_number')
            ->orderBy('week_number')
            ->get();
    }

    #[Computed]
    public function grandTotal(): int
    {
        return (int) Installment::query()
            ->when($this->filterPeriod, fn ($q) => $q->whereHas('order', fn ($o) => $o->where('period_name', $this->filterPeriod)))
            ->sum('amount_paid');
    }

    #[Computed]
    public function totalWeeksPaid(): int
    {
        return (int) Installment::query()
            ->when($this->filterPeriod, fn ($q) => $q->whereHas('order', fn ($o) => $o->where('period_name', $this->filterPeriod)))
            ->count();
    }

    #[Computed]
    public function totalOrders(): int
    {
        return Order::query()
            ->when($this->filterPeriod, fn ($q) => $q->where('period_name', $this->filterPeriod))
            ->count();
    }
}
?>

<div class="flex flex-col gap-6">

    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <flux:heading size="xl">{{ __('Laporan Keuangan') }}</flux:heading>

        <flux:field class="max-w-xs">
            <flux:select wire:model.live="filterPeriod" placeholder="{{ __('Semua Periode') }}">
                <flux:select.option value="">{{ __('Semua Periode') }}</flux:select.option>
                @foreach ($this->periods as $period)
                    <flux:select.option value="{{ $period }}">{{ $period }}</flux:select.option>
                @endforeach
            </flux:select>
        </flux:field>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-5">
            <flux:text size="sm" class="text-zinc-400">{{ __('Total Uang Masuk') }}</flux:text>
            <div class="text-2xl font-bold text-green-600 mt-1">
                Rp {{ number_format($this->grandTotal, 0, ',', '.') }}
            </div>
        </div>
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-5">
            <flux:text size="sm" class="text-zinc-400">{{ __('Total Minggu Terbayar') }}</flux:text>
            <div class="text-2xl font-bold text-zinc-800 dark:text-zinc-100 mt-1">
                {{ number_format($this->totalWeeksPaid) }} minggu
            </div>
        </div>
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-5">
            <flux:text size="sm" class="text-zinc-400">{{ __('Jumlah Order Aktif') }}</flux:text>
            <div class="text-2xl font-bold text-zinc-800 dark:text-zinc-100 mt-1">
                {{ number_format($this->totalOrders) }} order
            </div>
        </div>
    </div>

    {{-- Weekly Breakdown Table --}}
    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 overflow-hidden">
        <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-800 bg-white dark:bg-zinc-900">
            <flux:heading>{{ __('Rekap Per Minggu') }}</flux:heading>
        </div>

        <flux:table>
            <flux:table.columns>
                <flux:table.column>{{ __('Minggu ke-') }}</flux:table.column>
                <flux:table.column>{{ __('Jumlah Peserta Bayar') }}</flux:table.column>
                <flux:table.column>{{ __('Total Uang Masuk') }}</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($this->weeklyBreakdown as $row)
                    <flux:table.row wire:key="week-{{ $row->week_number }}">
                        <flux:table.cell variant="strong">Minggu {{ $row->week_number }}</flux:table.cell>
                        <flux:table.cell>{{ $row->participants_count }} peserta</flux:table.cell>
                        <flux:table.cell>
                            <flux:badge color="green" size="sm">
                                Rp {{ number_format($row->total, 0, ',', '.') }}
                            </flux:badge>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="3" class="text-center text-zinc-400 py-8">
                            {{ __('Belum ada data angsuran.') }}
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>

        @if ($this->weeklyBreakdown->isNotEmpty())
            <div class="px-5 py-4 border-t border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 flex justify-between items-center">
                <flux:text class="font-semibold">{{ __('Total Keseluruhan') }}</flux:text>
                <flux:text class="font-bold text-green-600">
                    Rp {{ number_format($this->grandTotal, 0, ',', '.') }}
                </flux:text>
            </div>
        @endif
    </div>

</div>
