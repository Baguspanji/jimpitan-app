<?php

use App\Models\Participant;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

new #[Title('Tabungan Peserta')] class extends Component {
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function participants(): \Illuminate\Pagination\LengthAwarePaginator
    {
        return Participant::with('savingRecord')
            ->when($this->search, fn ($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->orderBy('name')
            ->paginate(20);
    }
}
?>

<div class="flex flex-col gap-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <flux:heading size="xl">{{ __('Tabungan Peserta') }}</flux:heading>
    </div>

    {{-- Search --}}
    <div class="max-w-sm">
        <flux:input
            wire:model.live.debounce.300ms="search"
            icon="magnifying-glass"
            placeholder="{{ __('Cari peserta...') }}"
        />
    </div>

    {{-- Table --}}
    <flux:table>
        <flux:table.columns>
            <flux:table.column>{{ __('Nama Peserta') }}</flux:table.column>
            <flux:table.column>{{ __('No. HP') }}</flux:table.column>
            <flux:table.column>{{ __('Saldo') }}</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse ($this->participants as $participant)
                @php $balance = $participant->savingRecord?->balance ?? 0; @endphp
                <flux:table.row wire:key="{{ $participant->id }}">
                    <flux:table.cell variant="strong">{{ $participant->name }}</flux:table.cell>
                    <flux:table.cell>{{ $participant->phone ?? '-' }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:badge color="{{ $balance > 0 ? 'green' : 'zinc' }}" size="sm">
                            Rp {{ number_format($balance, 0, ',', '.') }}
                        </flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:button
                            size="sm"
                            icon="banknotes"
                            :href="route('savings.show', $participant)"
                        >
                            {{ __('Kelola') }}
                        </flux:button>
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="4" class="text-center text-zinc-400">
                        {{ __('Tidak ada peserta ditemukan.') }}
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>

    {{ $this->participants->links() }}
</div>
