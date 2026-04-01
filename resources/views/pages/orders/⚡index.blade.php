<?php

use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Participant;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

new #[Title('Order Undian')] class extends Component {
    use WithPagination;

    public ?int $editingId = null;
    public ?int $participant_id = null;
    public string $period_name = '';

    /** @var array<int, array{item_id: string, qty: string}> */
    public array $orderLines = [];

    public bool $showFormModal = false;
    public bool $showDeleteModal = false;
    public ?int $deletingId = null;

    public function mount(): void
    {
        $this->addLine();
    }

    public function addLine(): void
    {
        $this->orderLines[] = ['item_id' => '', 'qty' => '1'];
    }

    public function removeLine(int $index): void
    {
        unset($this->orderLines[$index]);
        $this->orderLines = array_values($this->orderLines);

        if (count($this->orderLines) === 0) {
            $this->addLine();
        }
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showFormModal = true;
    }

    public function openEdit(int $id): void
    {
        $order = Order::with('orderItems')->findOrFail($id);
        $this->editingId = $id;
        $this->participant_id = $order->participant_id;
        $this->period_name = $order->period_name;
        $this->orderLines = $order->orderItems
            ->map(fn (OrderItem $oi) => ['item_id' => (string) $oi->item_id, 'qty' => (string) $oi->qty])
            ->toArray();

        if (count($this->orderLines) === 0) {
            $this->addLine();
        }

        $this->showFormModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'participant_id'        => ['required', 'integer', 'exists:participants,id'],
            'period_name'           => ['required', 'string', 'max:255'],
            'orderLines'            => ['required', 'array', 'min:1'],
            'orderLines.*.item_id'  => ['required', 'integer', 'exists:items,id'],
            'orderLines.*.qty'      => ['required', 'integer', 'min:1'],
        ]);

        $orderData = [
            'participant_id' => $validated['participant_id'],
            'period_name'    => $validated['period_name'],
        ];

        if ($this->editingId) {
            $order = Order::findOrFail($this->editingId);
            $order->update($orderData);
            $order->orderItems()->delete();
        } else {
            $order = Order::create($orderData);
        }

        foreach ($validated['orderLines'] as $line) {
            $order->orderItems()->create([
                'item_id' => $line['item_id'],
                'qty'     => $line['qty'],
            ]);
        }

        $this->showFormModal = false;
        $this->resetForm();
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if ($this->deletingId) {
            Order::findOrFail($this->deletingId)->delete();
        }

        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->participant_id = null;
        $this->period_name = '';
        $this->orderLines = [['item_id' => '', 'qty' => '1']];
        $this->resetValidation();
    }

    #[Computed]
    public function orders(): \Illuminate\Pagination\LengthAwarePaginator
    {
        return Order::with(['participant', 'orderItems.item'])
            ->orderByDesc('id')
            ->paginate(20);
    }

    #[Computed]
    public function participants(): \Illuminate\Database\Eloquent\Collection
    {
        return Participant::orderBy('name')->get();
    }

    #[Computed]
    public function items(): \Illuminate\Database\Eloquent\Collection
    {
        return Item::orderBy('name')->get();
    }

    public function getWeeklyTotal(): int
    {
        $itemMap = Item::whereIn('id', collect($this->orderLines)->pluck('item_id')->filter()->unique())
            ->pluck('weekly_price', 'id');

        return collect($this->orderLines)->sum(fn ($line) => ($itemMap[$line['item_id']] ?? 0) * (int) ($line['qty'] ?: 0));
    }
}
?>

<div class="flex flex-col gap-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <flux:heading size="xl">{{ __('Order Undian') }}</flux:heading>
        <flux:button variant="primary" icon="plus" wire:click="openCreate">
            {{ __('Tambah Order') }}
        </flux:button>
    </div>

    {{-- Table --}}
    <flux:table>
        <flux:table.columns>
            <flux:table.column>{{ __('Peserta') }}</flux:table.column>
            <flux:table.column>{{ __('Periode') }}</flux:table.column>
            <flux:table.column>{{ __('Jml Barang') }}</flux:table.column>
            <flux:table.column>{{ __('Total / Minggu') }}</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse ($this->orders as $order)
                <flux:table.row wire:key="{{ $order->id }}">
                    <flux:table.cell variant="strong">{{ $order->participant->name }}</flux:table.cell>
                    <flux:table.cell>{{ $order->period_name }}</flux:table.cell>
                    <flux:table.cell>{{ $order->orderItems->count() }}</flux:table.cell>
                    <flux:table.cell>
                        Rp {{ number_format($order->weeklyTotal(), 0, ',', '.') }}
                    </flux:table.cell>
                    <flux:table.cell>
                        <div class="flex gap-2 justify-end">
                            <flux:button size="sm" icon="pencil" wire:click="openEdit({{ $order->id }})" />
                            <flux:button size="sm" variant="danger" icon="trash" wire:click="confirmDelete({{ $order->id }})" />
                        </div>
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="5" class="text-center text-zinc-400">
                        {{ __('Belum ada order.') }}
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>

    {{ $this->orders->links() }}

    {{-- Form Modal --}}
    <flux:modal wire:model="showFormModal" class="max-w-2xl">
        <form wire:submit="save" class="flex flex-col gap-5">
            <flux:heading>
                {{ $editingId ? __('Edit Order') : __('Tambah Order') }}
            </flux:heading>

            <div class="grid grid-cols-2 gap-4">
                <flux:field>
                    <flux:label>{{ __('Peserta') }}</flux:label>
                    <flux:select wire:model="participant_id">
                        <flux:select.option value="">{{ __('Pilih peserta...') }}</flux:select.option>
                        @foreach ($this->participants as $p)
                            <flux:select.option value="{{ $p->id }}">{{ $p->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="participant_id" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Periode') }}</flux:label>
                    <flux:input wire:model="period_name" placeholder="2025/2026" />
                    <flux:error name="period_name" />
                </flux:field>
            </div>

            {{-- Order Lines --}}
            <div class="flex flex-col gap-2">
                <flux:label>{{ __('Daftar Barang') }}</flux:label>
                <flux:error name="orderLines" />

                @foreach ($orderLines as $i => $line)
                    <div class="flex items-center gap-2" wire:key="line-{{ $i }}">
                        <flux:select wire:model.live="orderLines.{{ $i }}.item_id" class="flex-1">
                            <flux:select.option value="">{{ __('Pilih barang...') }}</flux:select.option>
                            @foreach ($this->items as $item)
                                <flux:select.option value="{{ $item->id }}">
                                    {{ $item->name }} - Rp {{ number_format($item->weekly_price, 0, ',', '.') }}/minggu
                                </flux:select.option>
                            @endforeach
                        </flux:select>

                        <div class="w-20">
                            <flux:input wire:model.live="orderLines.{{ $i }}.qty" type="number" min="1" />
                        </div>

                        <flux:button
                            type="button"
                            variant="ghost"
                            icon="x-mark"
                            wire:click="removeLine({{ $i }})"
                        />
                    </div>
                @endforeach

                <flux:button type="button" variant="ghost" icon="plus" wire:click="addLine" class="self-start">
                    {{ __('Tambah Barang') }}
                </flux:button>
            </div>

            {{-- Weekly Total --}}
            <div class="rounded-lg bg-zinc-50 dark:bg-zinc-800 p-4">
                <div class="flex justify-between">
                    <flux:text>{{ __('Total per Minggu') }}</flux:text>
                    <flux:text class="font-semibold">
                        Rp {{ number_format($this->getWeeklyTotal(), 0, ',', '.') }}
                    </flux:text>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <flux:button type="button" variant="ghost" wire:click="$set('showFormModal', false)">
                    {{ __('Batal') }}
                </flux:button>
                <flux:button type="submit" variant="primary">
                    {{ __('Simpan') }}
                </flux:button>
            </div>
        </form>
    </flux:modal>

    {{-- Delete Modal --}}
    <flux:modal wire:model="showDeleteModal" class="max-w-sm">
        <div class="flex flex-col gap-6">
            <flux:heading>{{ __('Hapus Order') }}</flux:heading>
            <flux:text>{{ __('Yakin ingin menghapus order ini beserta seluruh data angsurannya?') }}</flux:text>
            <div class="flex justify-end gap-3">
                <flux:button variant="ghost" wire:click="$set('showDeleteModal', false)">{{ __('Batal') }}</flux:button>
                <flux:button variant="danger" wire:click="delete">{{ __('Hapus') }}</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
