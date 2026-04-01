<?php

use App\Models\Installment;
use App\Models\Order;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

new #[Title('Angsuran')] class extends Component {
    #[Url]
    public string $filterOrder = '';

    public const WEEKS = 45;

    public function toggleInstallment(int $orderId, int $week): void
    {
        $existing = Installment::where('order_id', $orderId)
            ->where('week_number', $week)
            ->first();

        if ($existing) {
            $existing->delete();
        } else {
            Installment::create([
                'order_id'     => $orderId,
                'week_number'  => $week,
                'amount_paid'  => $this->orderModel?->weeklyTotal() ?? 0,
                'payment_date' => now()->toDateString(),
            ]);
        }

        unset($this->installmentMap);
    }

    public function updatingFilterOrder(): void
    {
        unset($this->installmentMap, $this->orderModel);
    }

    #[Computed]
    public function orders(): \Illuminate\Database\Eloquent\Collection
    {
        return Order::with('participant')
            ->orderBy('period_name')
            ->get();
    }

    #[Computed]
    public function orderModel(): ?Order
    {
        if (! $this->filterOrder) {
            return null;
        }

        return Order::with(['participant', 'orderItems.item'])
            ->find($this->filterOrder);
    }

    #[Computed]
    public function installmentMap(): array
    {
        if (! $this->orderModel) {
            return [];
        }

        return Installment::where('order_id', $this->orderModel->id)
            ->pluck('payment_date', 'week_number')
            ->toArray();
    }

    public function weeks(): array
    {
        return range(1, self::WEEKS);
    }
}
?>

<div class="flex flex-col gap-6" x-data="{ pendingWeek: null, pendingPaid: false }">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <flux:heading size="xl">{{ __('Kartu Angsuran') }}</flux:heading>
        @if ($this->orderModel)
            <flux:button
                icon="printer"
                size="sm"
                variant="ghost"
                :href="route('participants.slip.download', [$this->orderModel->participant_id, $this->orderModel->id])"
            >
                {{ __('Cetak Slip') }}
            </flux:button>
        @endif
    </div>

    {{-- Order Selector --}}
    <div class="flex items-center gap-4">
        <flux:field class="max-w-sm w-full">
            <flux:label>{{ __('Pilih Order') }}</flux:label>
            <flux:select wire:model.live="filterOrder">
                <flux:select.option value="">{{ __('-- Pilih peserta/periode --') }}</flux:select.option>
                @foreach ($this->orders as $order)
                    <flux:select.option value="{{ $order->id }}">
                        {{ $order->participant->name }} – {{ $order->period_name }}
                    </flux:select.option>
                @endforeach
            </flux:select>
        </flux:field>
    </div>

    @if ($this->orderModel)
        {{-- Order Summary --}}
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-4 grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div>
                <flux:text size="sm" class="text-zinc-400">{{ __('Peserta') }}</flux:text>
                <flux:text class="font-semibold">{{ $this->orderModel->participant->name }}</flux:text>
            </div>
            <div>
                <flux:text size="sm" class="text-zinc-400">{{ __('Periode') }}</flux:text>
                <flux:text class="font-semibold">{{ $this->orderModel->period_name }}</flux:text>
            </div>
            <div>
                <flux:text size="sm" class="text-zinc-400">{{ __('Iuran / Minggu') }}</flux:text>
                <flux:text class="font-semibold">Rp {{ number_format($this->orderModel->weeklyTotal(), 0, ',', '.') }}</flux:text>
            </div>
            <div>
                <flux:text size="sm" class="text-zinc-400">{{ __('Total Lunas') }}</flux:text>
                <flux:text class="font-semibold text-green-600">
                    {{ count($this->installmentMap) }} / {{ self::WEEKS }} minggu
                </flux:text>
            </div>
        </div>

        {{-- Installment Grid --}}
        <div class="overflow-x-auto">
            <div class="grid grid-cols-9 gap-2 min-w-max">
                @foreach ($this->weeks() as $week)
                    @php
                        $paid = isset($this->installmentMap[$week]);
                    @endphp
                    <button
                        x-on:click="pendingWeek = {{ $week }}; pendingPaid = {{ $paid ? 'true' : 'false' }}; $flux.modal('confirm-installment').show()"
                        class="
                            flex flex-col items-center justify-center w-16 h-16 rounded-xl border-2 text-sm font-semibold
                            transition-colors cursor-pointer
                            {{ $paid
                                ? 'border-green-500 bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400'
                                : 'border-zinc-200 bg-white text-zinc-400 hover:border-zinc-400 dark:bg-zinc-800 dark:border-zinc-700'
                            }}
                        "
                        title="{{ $paid ? 'Lunas - ' . $this->installmentMap[$week] : 'Klik untuk tandai lunas' }}"
                    >
                        <span class="text-xs text-zinc-400">W</span>
                        <span>{{ $week }}</span>
                        @if ($paid)
                            <flux:icon.check-circle class="size-3 text-green-500" />
                        @endif
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Remaining Summary --}}
        @php
            $paidCount = count($this->installmentMap);
            $remaining = self::WEEKS - $paidCount;
            $totalRemaining = $remaining * $this->orderModel->weeklyTotal();
        @endphp

        <div class="flex gap-6 text-sm">
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 rounded border-2 border-green-500 bg-green-50"></div>
                <span>Lunas: <strong>{{ $paidCount }}</strong> minggu</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 rounded border-2 border-zinc-200 bg-white dark:bg-zinc-800 dark:border-zinc-700"></div>
                <span>Sisa: <strong>{{ $remaining }}</strong> minggu
                    (Rp {{ number_format($totalRemaining, 0, ',', '.') }})
                </span>
            </div>
        </div>

        {{-- Confirmation Modal --}}
        <flux:modal name="confirm-installment" class="min-w-88">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg" x-text="pendingPaid ? 'Hapus Angsuran' : 'Konfirmasi Angsuran'"></flux:heading>
                    <flux:text class="mt-2 text-sm text-zinc-500">
                        <template x-if="!pendingPaid">
                            <span>Tandai minggu ke-<span x-text="pendingWeek"></span> sebagai <strong>lunas</strong>?</span>
                        </template>
                        <template x-if="pendingPaid">
                            <span>Hapus tanda lunas untuk minggu ke-<span x-text="pendingWeek"></span>?</span>
                        </template>
                    </flux:text>
                </div>
                <div class="flex gap-2 justify-end">
                    <flux:modal.close>
                        <flux:button variant="ghost">{{ __('Batal') }}</flux:button>
                    </flux:modal.close>
                    <flux:button
                        x-show="!pendingPaid"
                        variant="primary"
                        wire:loading.attr="disabled"
                        wire:target="toggleInstallment"
                        x-on:click="$wire.toggleInstallment({{ $this->orderModel->id }}, pendingWeek); $flux.modal('confirm-installment').close()"
                    >
                        {{ __('Konfirmasi') }}
                    </flux:button>
                    <flux:button
                        x-show="pendingPaid"
                        variant="danger"
                        wire:loading.attr="disabled"
                        wire:target="toggleInstallment"
                        x-on:click="$wire.toggleInstallment({{ $this->orderModel->id }}, pendingWeek); $flux.modal('confirm-installment').close()"
                    >
                        {{ __('Hapus') }}
                    </flux:button>
                </div>
            </div>
        </flux:modal>
    @else
        <div class="text-center py-12 text-zinc-400">
            {{ __('Pilih order di atas untuk menampilkan kartu angsuran.') }}
        </div>
    @endif

</div>
