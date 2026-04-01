<?php

use App\Models\Category;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Kategori')] class extends Component {
    public ?int $editingId = null;
    public string $name = '';

    public bool $showFormModal = false;
    public bool $showDeleteModal = false;
    public ?int $deletingId = null;

    public function openCreate(): void
    {
        $this->resetForm();
        $this->editingId = null;
        $this->showFormModal = true;
    }

    public function openEdit(int $id): void
    {
        $category = Category::findOrFail($id);
        $this->editingId = $id;
        $this->name = $category->name;
        $this->showFormModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        if ($this->editingId) {
            Category::findOrFail($this->editingId)->update($validated);
        } else {
            Category::create($validated);
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
            Category::findOrFail($this->deletingId)->delete();
        }

        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    private function resetForm(): void
    {
        $this->name = '';
        $this->editingId = null;
        $this->resetValidation();
    }

    #[Computed]
    public function categories(): \Illuminate\Database\Eloquent\Collection
    {
        return Category::withCount('items')->orderBy('name')->get();
    }
}
?>

<div class="flex flex-col gap-6">

    {{-- Header --}}
        <div class="flex items-center justify-between">
            <flux:heading size="xl">{{ __('Kategori') }}</flux:heading>
            <flux:button variant="primary" icon="plus" wire:click="openCreate">
                {{ __('Tambah Kategori') }}
            </flux:button>
        </div>

        {{-- Table --}}
        <flux:table>
            <flux:table.columns>
                <flux:table.column>{{ __('Nama Kategori') }}</flux:table.column>
                <flux:table.column>{{ __('Jumlah Barang') }}</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($this->categories as $category)
                    <flux:table.row wire:key="{{ $category->id }}">
                        <flux:table.cell variant="strong">{{ $category->name }}</flux:table.cell>
                        <flux:table.cell>
                            <flux:badge color="zinc" size="sm">{{ $category->items_count }}</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="flex gap-2 justify-end">
                                <flux:button size="sm" icon="pencil" wire:click="openEdit({{ $category->id }})" />
                                <flux:button size="sm" variant="danger" icon="trash" wire:click="confirmDelete({{ $category->id }})" />
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="3" class="text-center text-zinc-400">
                            {{ __('Belum ada kategori.') }}
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>

    {{-- Form Modal: Create / Edit --}}
    <flux:modal wire:model="showFormModal" class="max-w-sm">
        <form wire:submit="save" class="flex flex-col gap-6">
            <flux:heading>
                {{ $editingId ? __('Edit Kategori') : __('Tambah Kategori') }}
            </flux:heading>

            <flux:field>
                <flux:label>{{ __('Nama Kategori') }}</flux:label>
                <flux:input wire:model="name" autofocus placeholder="Contoh: Sembako, Snack, Minuman" />
                <flux:error name="name" />
            </flux:field>

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

    {{-- Delete Confirmation Modal --}}
    <flux:modal wire:model="showDeleteModal" class="max-w-sm">
        <div class="flex flex-col gap-6">
            <flux:heading>{{ __('Hapus Kategori') }}</flux:heading>
            <flux:text>{{ __('Yakin ingin menghapus kategori ini? Seluruh barang dalam kategori ini juga akan terhapus.') }}</flux:text>
            <div class="flex justify-end gap-3">
                <flux:button variant="ghost" wire:click="$set('showDeleteModal', false)">
                    {{ __('Batal') }}
                </flux:button>
                <flux:button variant="danger" wire:click="delete" wire:loading.attr="disabled" wire:target="delete">
                    {{ __('Hapus') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
