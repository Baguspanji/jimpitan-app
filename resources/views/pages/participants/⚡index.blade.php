<?php

use App\Models\Participant;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

new #[Title('Peserta')] class extends Component {
    use WithPagination;

    public string $search = '';
    public ?int $editingId = null;

    /** Form fields */
    public string $name = '';
    public string $phone = '';
    public string $address = '';
    public string $notes = '';

    public bool $showFormModal = false;
    public bool $showDeleteModal = false;
    public ?int $deletingId = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->editingId = null;
        $this->showFormModal = true;
    }

    public function openEdit(int $id): void
    {
        $participant = Participant::findOrFail($id);
        $this->editingId = $id;
        $this->name = $participant->name;
        $this->phone = $participant->phone;
        $this->address = $participant->address ?? '';
        $this->notes = $participant->notes ?? '';
        $this->showFormModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name'    => ['required', 'string', 'max:255'],
            'phone'   => ['required', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'notes'   => ['nullable', 'string', 'max:1000'],
        ]);

        if ($this->editingId) {
            Participant::findOrFail($this->editingId)->update($validated);
        } else {
            Participant::create($validated);
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
            Participant::findOrFail($this->deletingId)->delete();
        }

        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    private function resetForm(): void
    {
        $this->name = '';
        $this->phone = '';
        $this->address = '';
        $this->notes = '';
        $this->editingId = null;
        $this->resetValidation();
    }

    #[Computed]
    public function participants(): \Illuminate\Pagination\LengthAwarePaginator
    {
        return Participant::query()
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('phone', 'like', "%{$this->search}%"))
            ->orderBy('name')
            ->paginate(15);
    }
}
?>

<div class="flex flex-col gap-6">

    {{-- Header --}}
        <div class="flex items-center justify-between">
            <flux:heading size="xl">{{ __('Peserta') }}</flux:heading>
            <flux:button variant="primary" icon="plus" wire:click="openCreate">
                {{ __('Tambah Peserta') }}
            </flux:button>
        </div>

        {{-- Search --}}
        <flux:input
            wire:model.live.debounce.300ms="search"
            icon="magnifying-glass"
            placeholder="{{ __('Cari nama atau nomor HP...') }}"
            class="max-w-sm"
        />

        {{-- Table --}}
        <flux:table>
            <flux:table.columns>
                <flux:table.column>{{ __('Nama') }}</flux:table.column>
                <flux:table.column>{{ __('No. HP') }}</flux:table.column>
                <flux:table.column>{{ __('Alamat') }}</flux:table.column>
                <flux:table.column>{{ __('Catatan') }}</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($this->participants as $participant)
                    <flux:table.row wire:key="{{ $participant->id }}">
                        <flux:table.cell variant="strong">{{ $participant->name }}</flux:table.cell>
                        <flux:table.cell>{{ $participant->phone }}</flux:table.cell>
                        <flux:table.cell>{{ $participant->address ?? '-' }}</flux:table.cell>
                        <flux:table.cell>{{ $participant->notes ?? '-' }}</flux:table.cell>
                        <flux:table.cell>
                            <div class="flex gap-2 justify-end">
                                <flux:button size="sm" icon="pencil" wire:click="openEdit({{ $participant->id }})" />
                                <flux:button size="sm" variant="danger" icon="trash" wire:click="confirmDelete({{ $participant->id }})" />
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="5" class="text-center text-zinc-400">
                            {{ __('Belum ada peserta.') }}
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>

        {{ $this->participants->links() }}

    {{-- Form Modal: Create / Edit --}}
    <flux:modal wire:model="showFormModal" class="min-w-md max-w-lg">
        <form wire:submit="save" class="flex flex-col gap-6">
            <flux:heading>
                {{ $editingId ? __('Edit Peserta') : __('Tambah Peserta') }}
            </flux:heading>

            <flux:field>
                <flux:label>{{ __('Nama Lengkap') }}</flux:label>
                <flux:input wire:model="name" autofocus />
                <flux:error name="name" />
            </flux:field>

            <flux:field>
                <flux:label>{{ __('No. HP') }}</flux:label>
                <flux:input wire:model="phone" type="tel" />
                <flux:error name="phone" />
            </flux:field>

            <flux:field>
                <flux:label>{{ __('Alamat (RT/RW)') }}</flux:label>
                <flux:input wire:model="address" />
                <flux:error name="address" />
            </flux:field>

            <flux:field>
                <flux:label>{{ __('Catatan') }}</flux:label>
                <flux:textarea wire:model="notes" rows="3" />
                <flux:error name="notes" />
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
            <flux:heading>{{ __('Hapus Peserta') }}</flux:heading>
            <flux:text>{{ __('Yakin ingin menghapus peserta ini? Seluruh data order peserta juga akan terhapus.') }}</flux:text>
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
