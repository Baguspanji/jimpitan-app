<?php

use App\Models\Category;
use App\Models\Item;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

new #[Title('Barang')] class extends Component {
    use WithPagination;

    public string $filterCategory = '';
    public string $filterType = '';

    public ?int $editingId = null;

    /** Form fields */
    public ?int $category_id = null;
    public string $name = '';
    public string $unit = '';
    public string $weekly_price = '';
    public string $type = 'regular';
    public string $bonus_desc = '';

    /** Package items: array of item IDs */
    public array $packageItemIds = [];

    public bool $showFormModal = false;
    public bool $showDeleteModal = false;
    public ?int $deletingId = null;

    public function updatingFilterCategory(): void
    {
        $this->resetPage();
    }

    public function updatingFilterType(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showFormModal = true;
    }

    public function openEdit(int $id): void
    {
        $item = Item::with('packageItems')->findOrFail($id);
        $this->editingId = $id;
        $this->category_id = $item->category_id;
        $this->name = $item->name;
        $this->unit = $item->unit;
        $this->weekly_price = (string) $item->weekly_price;
        $this->type = $item->type;
        $this->bonus_desc = $item->bonus_desc ?? '';
        $this->packageItemIds = $item->packageItems->pluck('id')->map(fn ($v) => (string) $v)->toArray();
        $this->showFormModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'category_id'    => ['required', 'integer', 'exists:categories,id'],
            'name'           => ['required', 'string', 'max:255'],
            'unit'           => ['required', 'string', 'max:100'],
            'weekly_price'   => ['required', 'integer', 'min:0'],
            'type'           => ['required', 'in:regular,package'],
            'bonus_desc'     => ['nullable', 'string', 'max:255'],
            'packageItemIds' => ['nullable', 'array'],
            'packageItemIds.*' => ['integer', 'exists:items,id'],
        ]);

        $itemData = [
            'category_id'  => $validated['category_id'],
            'name'         => $validated['name'],
            'unit'         => $validated['unit'],
            'weekly_price' => $validated['weekly_price'],
            'type'         => $validated['type'],
            'bonus_desc'   => $validated['bonus_desc'] ?: null,
        ];

        if ($this->editingId) {
            $item = Item::findOrFail($this->editingId);
            $item->update($itemData);
        } else {
            $item = Item::create($itemData);
        }

        if ($validated['type'] === 'package') {
            $item->packageItems()->sync($validated['packageItemIds'] ?? []);
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
            Item::findOrFail($this->deletingId)->delete();
        }

        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->category_id = null;
        $this->name = '';
        $this->unit = '';
        $this->weekly_price = '';
        $this->type = 'regular';
        $this->bonus_desc = '';
        $this->packageItemIds = [];
        $this->resetValidation();
    }

    #[Computed]
    public function items(): \Illuminate\Pagination\LengthAwarePaginator
    {
        return Item::with('category')
            ->when($this->filterCategory, fn ($q) => $q->where('category_id', $this->filterCategory))
            ->when($this->filterType, fn ($q) => $q->where('type', $this->filterType))
            ->orderBy('name')
            ->paginate(20);
    }

    #[Computed]
    public function categories(): \Illuminate\Database\Eloquent\Collection
    {
        return Category::orderBy('name')->get();
    }

    #[Computed]
    public function regularItems(): \Illuminate\Database\Eloquent\Collection
    {
        return Item::where('type', 'regular')->orderBy('name')->get();
    }
}
?>

<div class="flex flex-col gap-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <flux:heading size="xl">{{ __('Barang & Paket') }}</flux:heading>
        <flux:button variant="primary" icon="plus" wire:click="openCreate">
            {{ __('Tambah Barang') }}
        </flux:button>
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap gap-3">
        <flux:select wire:model.live="filterCategory" class="max-w-xs" placeholder="{{ __('Semua Kategori') }}">
            <flux:select.option value="">{{ __('Semua Kategori') }}</flux:select.option>
            @foreach ($this->categories as $cat)
                <flux:select.option value="{{ $cat->id }}">{{ $cat->name }}</flux:select.option>
            @endforeach
        </flux:select>

        <flux:select wire:model.live="filterType" class="max-w-xs" placeholder="{{ __('Semua Tipe') }}">
            <flux:select.option value="">{{ __('Semua Tipe') }}</flux:select.option>
            <flux:select.option value="regular">{{ __('Reguler') }}</flux:select.option>
            <flux:select.option value="package">{{ __('Paket') }}</flux:select.option>
        </flux:select>
    </div>

    {{-- Table --}}
    <flux:table>
        <flux:table.columns>
            <flux:table.column>{{ __('Nama Barang') }}</flux:table.column>
            <flux:table.column>{{ __('Kategori') }}</flux:table.column>
            <flux:table.column>{{ __('Satuan') }}</flux:table.column>
            <flux:table.column>{{ __('Harga/Minggu') }}</flux:table.column>
            <flux:table.column>{{ __('Tipe') }}</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse ($this->items as $item)
                <flux:table.row wire:key="{{ $item->id }}">
                    <flux:table.cell variant="strong">
                        {{ $item->name }}
                        @if ($item->bonus_desc)
                            <flux:text size="sm" class="text-zinc-400">{{ $item->bonus_desc }}</flux:text>
                        @endif
                    </flux:table.cell>
                    <flux:table.cell>{{ $item->category->name }}</flux:table.cell>
                    <flux:table.cell>{{ $item->unit }}</flux:table.cell>
                    <flux:table.cell>Rp {{ number_format($item->weekly_price, 0, ',', '.') }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:badge color="{{ $item->type === 'package' ? 'blue' : 'zinc' }}" size="sm">
                            {{ $item->type === 'package' ? 'Paket' : 'Reguler' }}
                        </flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>
                        <div class="flex gap-2 justify-end">
                            <flux:button size="sm" icon="pencil" wire:click="openEdit({{ $item->id }})" />
                            <flux:button size="sm" variant="danger" icon="trash" wire:click="confirmDelete({{ $item->id }})" />
                        </div>
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="6" class="text-center text-zinc-400">
                        {{ __('Belum ada barang.') }}
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>

    {{ $this->items->links() }}

    {{-- Form Modal --}}
    <flux:modal wire:model="showFormModal" class="max-w-lg">
        <form wire:submit="save" class="flex flex-col gap-5">
            <flux:heading>
                {{ $editingId ? __('Edit Barang') : __('Tambah Barang') }}
            </flux:heading>

            <flux:field>
                <flux:label>{{ __('Kategori') }}</flux:label>
                <flux:select wire:model="category_id">
                    <flux:select.option value="">{{ __('Pilih kategori...') }}</flux:select.option>
                    @foreach ($this->categories as $cat)
                        <flux:select.option value="{{ $cat->id }}">{{ $cat->name }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:error name="category_id" />
            </flux:field>

            <flux:field>
                <flux:label>{{ __('Tipe') }}</flux:label>
                <flux:select wire:model.live="type">
                    <flux:select.option value="regular">{{ __('Reguler') }}</flux:select.option>
                    <flux:select.option value="package">{{ __('Paket') }}</flux:select.option>
                </flux:select>
            </flux:field>

            <flux:field>
                <flux:label>{{ __('Nama') }}</flux:label>
                <flux:input wire:model="name" autofocus />
                <flux:error name="name" />
            </flux:field>

            <div class="grid grid-cols-2 gap-4">
                <flux:field>
                    <flux:label>{{ __('Satuan') }}</flux:label>
                    <flux:input wire:model="unit" placeholder="Kg, Dos, Blek..." />
                    <flux:error name="unit" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Harga / Minggu (Rp)') }}</flux:label>
                    <flux:input wire:model="weekly_price" type="number" min="0" />
                    <flux:error name="weekly_price" />
                </flux:field>
            </div>

            @if ($type === 'package')
                <flux:field>
                    <flux:label>{{ __('Bonus Paket') }}</flux:label>
                    <flux:input wire:model="bonus_desc" placeholder="Contoh: Handuk Tebal, Panci..." />
                    <flux:error name="bonus_desc" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Barang Isi Paket') }}</flux:label>
                    <div class="flex flex-col gap-2 rounded-lg border border-zinc-200 p-3 dark:border-zinc-700 max-h-48 overflow-y-auto">
                        @foreach ($this->regularItems as $regularItem)
                            <label class="flex items-center gap-2 cursor-pointer">
                                <flux:checkbox
                                    wire:model="packageItemIds"
                                    value="{{ $regularItem->id }}"
                                />
                                <span class="text-sm">{{ $regularItem->name }} ({{ $regularItem->unit }})</span>
                            </label>
                        @endforeach
                    </div>
                    <flux:error name="packageItemIds" />
                </flux:field>
            @endif

            <div class="flex justify-end gap-3">
                <flux:button type="button" variant="ghost" wire:click="$set('showFormModal', false)">
                    {{ __('Batal') }}
                </flux:button>
                <flux:button type="submit" variant="primary" wire:loading.attr="disabled">
                    {{ __('Simpan') }}
                </flux:button>
            </div>
        </form>
    </flux:modal>

    {{-- Delete Modal --}}
    <flux:modal wire:model="showDeleteModal" class="max-w-sm">
        <div class="flex flex-col gap-6">
            <flux:heading>{{ __('Hapus Barang') }}</flux:heading>
            <flux:text>{{ __('Yakin ingin menghapus barang ini?') }}</flux:text>
            <div class="flex justify-end gap-3">
                <flux:button variant="ghost" wire:click="$set('showDeleteModal', false)">{{ __('Batal') }}</flux:button>
                <flux:button variant="danger" wire:click="delete" wire:loading.attr="disabled" wire:target="delete">{{ __('Hapus') }}</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
