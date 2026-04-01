<?php

use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

new #[Title('Laporan Belanja (Kulakan)')] class extends Component {
    #[Url]
    public string $filterPeriod = '';

    #[Computed]
    public function periods(): \Illuminate\Support\Collection
    {
        return Order::distinct()->orderByDesc('period_name')->pluck('period_name');
    }

    /**
     * @return \Illuminate\Support\Collection<int, object{id: int, name: string, unit: string, type: string, total_qty: int, total_orders: int}>
     */
    #[Computed]
    public function itemSummary(): \Illuminate\Support\Collection
    {
        return OrderItem::query()
            ->when($this->filterPeriod, fn ($q) => $q->whereHas('order', fn ($o) => $o->where('period_name', $this->filterPeriod)))
            ->selectRaw('item_id, SUM(qty) as total_qty, COUNT(DISTINCT order_id) as total_orders')
            ->groupBy('item_id')
            ->orderByDesc('total_qty')
            ->with('item.category')
            ->get();
    }

    #[Computed]
    public function grandTotalQty(): int
    {
        return $this->itemSummary->sum('total_qty');
    }
}
?>

<div class="flex flex-col gap-6">

    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <flux:heading size="xl">{{ __('Laporan Belanja (Kulakan)') }}</flux:heading>

        <div class="flex flex-wrap items-center gap-3">
            <flux:field class="max-w-xs">
                <flux:select wire:model.live="filterPeriod" placeholder="{{ __('Semua Periode') }}">
                    <flux:select.option value="">{{ __('Semua Periode') }}</flux:select.option>
                    @foreach ($this->periods as $period)
                        <flux:select.option value="{{ $period }}">{{ $period }}</flux:select.option>
                    @endforeach
                </flux:select>
            </flux:field>

            <flux:button
                icon="document-arrow-down"
                size="sm"
                :href="route('reports.belanja.pdf', $filterPeriod ? ['period' => $filterPeriod] : [])"
            >
                PDF
            </flux:button>
            <flux:button
                icon="table-cells"
                size="sm"
                variant="ghost"
                :href="route('reports.belanja.excel', $filterPeriod ? ['period' => $filterPeriod] : [])"
            >
                Excel
            </flux:button>
        </div>
    </div>

    {{-- Info Banner --}}
    <flux:callout icon="information-circle" variant="info">
        <flux:callout.text>
            {{ __('Laporan ini menampilkan total kuantitas setiap barang yang perlu dikulakan berdasarkan order peserta.') }}
            @if ($this->filterPeriod)
                {{ __('Periode:') }} <strong>{{ $this->filterPeriod }}</strong>
            @endif
        </flux:callout.text>
    </flux:callout>

    {{-- Summary --}}
    @if ($this->itemSummary->isNotEmpty())
        <div class="grid grid-cols-2 gap-4">
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-5">
                <flux:text size="sm" class="text-zinc-400">{{ __('Total Jenis Barang') }}</flux:text>
                <div class="text-2xl font-bold text-zinc-800 dark:text-zinc-100 mt-1">
                    {{ $this->itemSummary->count() }} jenis
                </div>
            </div>
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-5">
                <flux:text size="sm" class="text-zinc-400">{{ __('Total Kuantitas Keseluruhan') }}</flux:text>
                <div class="text-2xl font-bold text-zinc-800 dark:text-zinc-100 mt-1">
                    {{ number_format($this->grandTotalQty) }}
                </div>
            </div>
        </div>
    @endif

    {{-- Items Table --}}
    <flux:table>
        <flux:table.columns>
            <flux:table.column>{{ __('Barang') }}</flux:table.column>
            <flux:table.column>{{ __('Kategori') }}</flux:table.column>
            <flux:table.column>{{ __('Tipe') }}</flux:table.column>
            <flux:table.column>{{ __('Satuan') }}</flux:table.column>
            <flux:table.column>{{ __('Total Pesanan') }}</flux:table.column>
            <flux:table.column>{{ __('Total Qty') }}</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse ($this->itemSummary as $row)
                <flux:table.row wire:key="{{ $row->item_id }}">
                    <flux:table.cell variant="strong">{{ $row->item->name }}</flux:table.cell>
                    <flux:table.cell>{{ $row->item->category->name }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:badge color="{{ $row->item->type === 'package' ? 'blue' : 'zinc' }}" size="sm">
                            {{ $row->item->type === 'package' ? 'Paket' : 'Reguler' }}
                        </flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>{{ $row->item->unit }}</flux:table.cell>
                    <flux:table.cell>{{ $row->total_orders }} peserta</flux:table.cell>
                    <flux:table.cell>
                        <flux:badge color="amber" size="sm">
                            {{ number_format($row->total_qty) }}
                        </flux:badge>
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="6" class="text-center text-zinc-400 py-8">
                        {{ __('Belum ada data order.') }}
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>

</div>
