<?php

use App\Models\Participant;
use App\Models\Saving;
use App\Models\SavingTransaction;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

new #[Title('Detail Tabungan')] class extends Component {
    use WithPagination;

    public Participant $participant;

    public string $txType = 'deposit';
    public string $txAmount = '';
    public string $txNotes = '';

    public bool $showFormModal = false;

    public function mount(Participant $participant): void
    {
        $this->participant = $participant;
    }

    public function openForm(string $type): void
    {
        $this->txType = $type;
        $this->txAmount = '';
        $this->txNotes = '';
        $this->resetValidation();
        $this->showFormModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'txType'   => ['required', 'in:deposit,withdrawal'],
            'txAmount' => ['required', 'integer', 'min:1'],
            'txNotes'  => ['nullable', 'string', 'max:255'],
        ]);

        $amount = (int) $validated['txAmount'];

        if ($validated['txType'] === 'withdrawal') {
            $currentBalance = Saving::where('participant_id', $this->participant->id)->value('balance') ?? 0;
            if ($currentBalance < $amount) {
                $this->addError('txAmount', 'Saldo tidak mencukupi untuk penarikan ini.');

                return;
            }
        }

        DB::transaction(function () use ($validated, $amount): void {
            $saving = Saving::firstOrCreate(
                ['participant_id' => $this->participant->id],
                ['balance' => 0],
            );

            if ($validated['txType'] === 'withdrawal') {
                $saving->decrement('balance', $amount);
            } else {
                $saving->increment('balance', $amount);
            }

            SavingTransaction::create([
                'participant_id'   => $this->participant->id,
                'type'             => $validated['txType'],
                'amount'           => $amount,
                'transaction_date' => now()->toDateString(),
                'note'             => $validated['txNotes'] ?: null,
                'created_by'       => auth()->id(),
            ]);
        });

        $this->showFormModal = false;

        unset($this->transactions, $this->saving);
    }

    #[Computed]
    public function saving(): ?Saving
    {
        return Saving::where('participant_id', $this->participant->id)->first();
    }

    #[Computed]
    public function transactions(): \Illuminate\Pagination\LengthAwarePaginator
    {
        return SavingTransaction::where('participant_id', $this->participant->id)
            ->with('createdBy')
            ->orderByDesc('transaction_date')
            ->orderByDesc('id')
            ->paginate(20);
    }
}
?>

<div class="flex flex-col gap-6">

    {{-- Back link --}}
    <div>
        <flux:button variant="ghost" icon="arrow-left" :href="route('savings.index')" size="sm">
            {{ __('Kembali ke Tabungan') }}
        </flux:button>
    </div>

    {{-- Participant Header --}}
    <div class="flex items-start justify-between flex-wrap gap-4">
        <div>
            <flux:heading size="xl">{{ $participant->name }}</flux:heading>
            @if ($participant->phone)
                <flux:text class="text-zinc-400">{{ $participant->phone }}</flux:text>
            @endif
        </div>

        <div class="flex items-center gap-3">
            <div class="text-right">
                <flux:text size="sm" class="text-zinc-400">{{ __('Saldo') }}</flux:text>
                <flux:text class="text-2xl font-bold text-green-600">
                    Rp {{ number_format($this->saving?->balance ?? 0, 0, ',', '.') }}
                </flux:text>
            </div>
            <flux:button variant="primary" icon="arrow-down-circle" wire:click="openForm('deposit')">
                {{ __('Setor') }}
            </flux:button>
            <flux:button variant="ghost" icon="arrow-up-circle" wire:click="openForm('withdrawal')">
                {{ __('Tarik') }}
            </flux:button>
        </div>
    </div>

    {{-- Transaction History --}}
    <flux:heading>{{ __('Riwayat Transaksi') }}</flux:heading>

    <flux:table>
        <flux:table.columns>
            <flux:table.column>{{ __('Tanggal') }}</flux:table.column>
            <flux:table.column>{{ __('Tipe') }}</flux:table.column>
            <flux:table.column>{{ __('Jumlah') }}</flux:table.column>
            <flux:table.column>{{ __('Keterangan') }}</flux:table.column>
            <flux:table.column>{{ __('Dicatat oleh') }}</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse ($this->transactions as $tx)
                <flux:table.row wire:key="{{ $tx->id }}">
                    <flux:table.cell>
                        {{ \Carbon\Carbon::parse($tx->transaction_date)->translatedFormat('d M Y') }}
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:badge color="{{ $tx->type === 'deposit' ? 'green' : 'red' }}" size="sm">
                            {{ $tx->type === 'deposit' ? 'Setor' : 'Tarik' }}
                        </flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>
                        <span class="{{ $tx->type === 'deposit' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $tx->type === 'deposit' ? '+' : '-' }}
                            Rp {{ number_format($tx->amount, 0, ',', '.') }}
                        </span>
                    </flux:table.cell>
                    <flux:table.cell>{{ $tx->note ?? '-' }}</flux:table.cell>
                    <flux:table.cell>{{ $tx->createdBy?->name ?? '-' }}</flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="5" class="text-center text-zinc-400">
                        {{ __('Belum ada transaksi.') }}
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>

    {{ $this->transactions->links() }}

    {{-- Transaction Modal --}}
    <flux:modal wire:model="showFormModal" class="max-w-sm">
        <form wire:submit="save" class="flex flex-col gap-5">
            <flux:heading>
                {{ $txType === 'deposit' ? __('Setor Tabungan') : __('Tarik Tabungan') }}
            </flux:heading>

            <div class="rounded-lg bg-zinc-50 dark:bg-zinc-800 p-3">
                <flux:text size="sm" class="text-zinc-400">{{ __('Saldo saat ini') }}</flux:text>
                <flux:text class="font-bold">Rp {{ number_format($this->saving?->balance ?? 0, 0, ',', '.') }}</flux:text>
            </div>

            <flux:field>
                <flux:label>{{ __('Jumlah (Rp)') }}</flux:label>
                <flux:input wire:model="txAmount" type="number" min="1" autofocus />
                <flux:error name="txAmount" />
            </flux:field>

            <flux:field>
                <flux:label>{{ __('Keterangan') }} <flux:badge size="sm" color="zinc">{{ __('Opsional') }}</flux:badge></flux:label>
                <flux:input wire:model="txNotes" />
                <flux:error name="txNotes" />
            </flux:field>

            <div class="flex justify-end gap-3">
                <flux:button type="button" variant="ghost" wire:click="$set('showFormModal', false)">
                    {{ __('Batal') }}
                </flux:button>
                <flux:button
                    type="submit"
                    variant="{{ $txType === 'deposit' ? 'primary' : 'danger' }}"
                >
                    {{ $txType === 'deposit' ? __('Setor') : __('Tarik') }}
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
