<?php

use App\Models\Order;
use App\Models\Participant;
use App\Models\ParticipantToken;
use Illuminate\Support\Str;
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

    /** Slip PDF */
    public bool $showSlipModal = false;
    public ?int $slipParticipantId = null;

    /** Token Akses Warga */
    public bool $showTokenModal = false;
    public ?int $tokenParticipantId = null;
    public int $tokenDays = 7;
    public string $generatedUrl = '';

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

    public function openSlipModal(int $id): void
    {
        $this->slipParticipantId = $id;
        $this->showSlipModal = true;
    }

    #[Computed]
    public function slipOrders(): \Illuminate\Database\Eloquent\Collection
    {
        if (! $this->slipParticipantId) {
            return new \Illuminate\Database\Eloquent\Collection();
        }

        return Order::where('participant_id', $this->slipParticipantId)
            ->orderByDesc('period_name')
            ->get();
    }

    public function openTokenModal(int $id): void
    {
        $this->tokenParticipantId = $id;
        $this->generatedUrl = '';
        $this->tokenDays = 7;
        $this->showTokenModal = true;
    }

    public function generateToken(): void
    {
        $this->validate(['tokenDays' => ['required', 'integer', 'in:1,7,30']]);

        $participant = Participant::findOrFail($this->tokenParticipantId);
        $participant->token()->delete();

        $token = ParticipantToken::create([
            'participant_id' => $participant->id,
            'token'          => Str::random(64),
            'expires_at'     => now()->addDays($this->tokenDays),
        ]);

        $this->generatedUrl = route('warga.dashboard', $token->token);
    }

    public function revokeToken(int $id): void
    {
        Participant::findOrFail($id)->token()->delete();

        if ($this->tokenParticipantId === $id) {
            $this->generatedUrl = '';
        }
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
            ->with('token')
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
                <flux:table.column>{{ __('Akses Warga') }}</flux:table.column>
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
                            @if ($participant->token && ! $participant->token->isExpired())
                                <flux:badge color="green" size="sm">{{ __('Aktif') }}</flux:badge>
                            @elseif ($participant->token && $participant->token->isExpired())
                                <flux:badge color="zinc" size="sm">{{ __('Kedaluwarsa') }}</flux:badge>
                            @else
                                <span class="text-zinc-400 text-sm">—</span>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="flex gap-2 justify-end">
                                <flux:button size="sm" icon="printer" wire:click="openSlipModal({{ $participant->id }})" />
                                <flux:button size="sm" icon="link" wire:click="openTokenModal({{ $participant->id }})" />
                                <flux:button size="sm" icon="pencil" wire:click="openEdit({{ $participant->id }})" />
                                <flux:button size="sm" variant="danger" icon="trash" wire:click="confirmDelete({{ $participant->id }})" />
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="6" class="text-center text-zinc-400">
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

    {{-- Slip PDF Modal --}}
    <flux:modal wire:model="showSlipModal" class="max-w-sm">
        <div class="flex flex-col gap-4">
            <flux:heading>{{ __('Cetak Slip') }}</flux:heading>
            @if ($this->slipOrders->isEmpty())
                <flux:text>{{ __('Peserta belum memiliki order.') }}</flux:text>
                <div class="flex justify-end">
                    <flux:button variant="ghost" wire:click="$set('showSlipModal', false)">{{ __('Tutup') }}</flux:button>
                </div>
            @else
                <flux:text>{{ __('Pilih periode untuk diunduh sebagai PDF:') }}</flux:text>
                <div class="flex flex-col gap-2">
                    @foreach ($this->slipOrders as $order)
                        <flux:button
                            icon="arrow-down-tray"
                            :href="route('participants.slip.download', [$slipParticipantId, $order])"
                        >
                            {{ $order->period_name }}
                        </flux:button>
                    @endforeach
                </div>
                <div class="flex justify-end">
                    <flux:button variant="ghost" wire:click="$set('showSlipModal', false)">{{ __('Batal') }}</flux:button>
                </div>
            @endif
        </div>
    </flux:modal>

    {{-- Token Akses Warga Modal --}}
    <flux:modal wire:model="showTokenModal" class="max-w-sm">
        <div class="flex flex-col gap-4" x-data="{ copied: false }">
            <flux:heading>{{ __('Bagikan Akses Warga') }}</flux:heading>

            <flux:field>
                <flux:label>{{ __('Masa berlaku link') }}</flux:label>
                <flux:select wire:model.live="tokenDays">
                    <flux:select.option value="1">{{ __('1 Hari') }}</flux:select.option>
                    <flux:select.option value="7">{{ __('7 Hari') }}</flux:select.option>
                    <flux:select.option value="30">{{ __('30 Hari') }}</flux:select.option>
                </flux:select>
                <flux:error name="tokenDays" />
            </flux:field>

            @if ($generatedUrl)
                <flux:field>
                    <flux:label>{{ __('Link Akses') }}</flux:label>
                    <div class="flex gap-2">
                        <flux:input readonly :value="$generatedUrl" class="flex-1 text-xs" />
                        <flux:button
                            size="sm"
                            x-on:click="navigator.clipboard.writeText($wire.generatedUrl).then(() => { copied = true; setTimeout(() => copied = false, 2000) })"
                        >
                            <span x-show="!copied">{{ __('Salin') }}</span>
                            <span x-show="copied">{{ __('Tersalin!') }}</span>
                        </flux:button>
                    </div>
                </flux:field>
            @endif

            <div class="flex justify-between gap-3">
                @if ($tokenParticipantId && \App\Models\Participant::find($tokenParticipantId)?->token)
                    <flux:button variant="danger" size="sm" wire:click="revokeToken({{ $tokenParticipantId }})" wire:loading.attr="disabled" wire:target="revokeToken">
                        {{ __('Cabut Akses') }}
                    </flux:button>
                @else
                    <div></div>
                @endif
                <div class="flex gap-2">
                    <flux:button variant="ghost" wire:click="$set('showTokenModal', false)">{{ __('Tutup') }}</flux:button>
                    <flux:button variant="primary" wire:click="generateToken" wire:loading.attr="disabled" wire:target="generateToken">
                        {{ __('Buat Link') }}
                    </flux:button>
                </div>
            </div>
        </div>
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
