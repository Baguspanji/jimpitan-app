<?php

namespace App\Exports;

use App\Models\Participant;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class TabunganExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithTitle
{
    public function collection(): Collection
    {
        return Participant::with(['savingRecord', 'savingTransactions'])
            ->orderBy('name')
            ->get();
    }

    public function map(mixed $participant): array
    {
        $deposits = $participant->savingTransactions->where('type', 'deposit')->sum('amount');
        $withdrawals = $participant->savingTransactions->where('type', 'withdrawal')->sum('amount');
        $balance = $participant->savingRecord?->balance ?? 0;

        return [
            $participant->name,
            $deposits,
            $withdrawals,
            $balance,
            $participant->savingTransactions->count(),
        ];
    }

    public function headings(): array
    {
        return ['Peserta', 'Total Setoran (Rp)', 'Total Penarikan (Rp)', 'Saldo (Rp)', 'Jml Transaksi'];
    }

    public function title(): string
    {
        return 'Rekap Tabungan';
    }
}
