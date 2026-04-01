<?php

namespace App\Exports;

use App\Models\Installment;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class KeuanganExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithTitle
{
    public function __construct(private readonly string $period = '') {}

    public function collection(): Collection
    {
        return Installment::query()
            ->when($this->period, fn ($q) => $q->whereHas('order', fn ($o) => $o->where('period_name', $this->period)))
            ->selectRaw('week_number, SUM(amount_paid) as total, COUNT(*) as participants_count')
            ->groupBy('week_number')
            ->orderBy('week_number')
            ->get();
    }

    public function map(mixed $row): array
    {
        return [
            'Minggu '.$row->week_number,
            $row->participants_count,
            $row->total,
        ];
    }

    public function headings(): array
    {
        return ['Minggu ke-', 'Jumlah Peserta Bayar', 'Total Uang Masuk (Rp)'];
    }

    public function title(): string
    {
        return 'Rekap Keuangan';
    }
}
