<?php

use App\Models\Participant;
use App\Models\Saving;
use App\Models\SavingTransaction;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

new #[Title('Laporan Tabungan')] class extends Component {
    #[Url]
    public string $search = '';

    #[Computed]
    public function totalBalance(): int
    {
        return (int) Saving::sum('balance');
    }

    #[Computed]
    public function totalDeposits(): int
    {
        return (int) SavingTransaction::where('type', 'deposit')->sum('amount');
    }

    #[Computed]
    public function totalWithdrawals(): int
    {
        return (int) SavingTransaction::where('type', 'withdrawal')->sum('amount');
    }

    #[Computed]
    public function participants(): \Illuminate\Database\Eloquent\Collection
    {
        return Participant::query()
            ->with(['savingRecord', 'savingTransactions'])
            ->when($this->search, fn ($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->orderBy('name')
            ->get();
    }
}
?>

<div class="flex flex-col gap-6">

    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <flux:heading size="xl">{{ __('Laporan Tabungan') }}</flux:heading>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-5">
            <flux:text size="sm" class="text-zinc-400">{{ __('Total Saldo Aktif') }}</flux:text>
            <div class="text-2xl font-bold text-green-600 mt-1">
                Rp {{ number_format($this->totalBalance, 0, ',', '.') }}
            </div>
        </div>
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-5">
            <flux:text size="sm" class="text-zinc-400">{{ __('Total Setoran') }}</flux:text>
            <div class="text-2xl font-bold text-zinc-800 dark:text-zinc-100 mt-1">
                Rp {{ number_format($this->totalDeposits, 0, ',', '.') }}
            </div>
        </div>
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-5">
            <flux:text size="sm" class="text-zinc-400">{{ __('Total Penarikan') }}</flux:text>
            <div class="text-2xl font-bold text-zinc-800 dark:text-zinc-100 mt-1">
                Rp {{ number_format($this->totalWithdrawals, 0, ',', '.') }}
            </div>
        </div>
    </div>

    {{-- Search --}}
    <div class="max-w-sm">
        <flux:input
            wire:model.live.debounce.300ms="search"
            icon="magnifying-glass"
            placeholder="{{ __('Cari peserta...') }}"
        />
    </div>

    {{-- Participants Savings Table --}}
    <flux:table>
        <flux:table.columns>
            <flux:table.column>{{ __('Peserta') }}</flux:table.column>
            <flux:table.column>{{ __('Total Setoran') }}</flux:table.column>
            <flux:table.column>{{ __('Total Penarikan') }}</flux:table.column>
            <flux:table.column>{{ __('Saldo') }}</flux:table.column>
            <flux:table.column>{{ __('Jml Transaksi') }}</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse ($this->participants as $participant)
                @php
                    $deposits = $participant->savingTransactions->where('type', 'deposit')->sum('amount');
                    $withdrawals = $participant->savingTransactions->where('type', 'withdrawal')->sum('amount');
                    $balance = $participant->savingRecord?->balance ?? 0;
                @endphp
                <flux:table.row wire:key="{{ $participant->id }}">
                    <flux:table.cell variant="strong">{{ $participant->name }}</flux:table.cell>
                    <flux:table.cell class="text-green-600">
                        Rp {{ number_format($deposits, 0, ',', '.') }}
                    </flux:table.cell>
                    <flux:table.cell class="text-red-500">
                        Rp {{ number_format($withdrawals, 0, ',', '.') }}
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:badge color="{{ $balance > 0 ? 'green' : 'zinc' }}" size="sm">
                            Rp {{ number_format($balance, 0, ',', '.') }}
                        </flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>
                        {{ $participant->savingTransactions->count() }} transaksi
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:button
                            size="sm"
                            variant="ghost"
                            icon="arrow-top-right-on-square"
                            :href="route('savings.show', $participant)"
                            wire:navigate
                        />
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="6" class="text-center text-zinc-400 py-8">
                        {{ __('Tidak ada data peserta.') }}
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>

</div>
