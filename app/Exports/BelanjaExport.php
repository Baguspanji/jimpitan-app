<?php

namespace App\Exports;

use App\Models\OrderItem;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class BelanjaExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithTitle
{
    public function __construct(private readonly string $period = '') {}

    public function collection(): Collection
    {
        return OrderItem::query()
            ->when($this->period, fn ($q) => $q->whereHas('order', fn ($o) => $o->where('period_name', $this->period)))
            ->selectRaw('item_id, SUM(qty) as total_qty, COUNT(DISTINCT order_id) as total_orders')
            ->groupBy('item_id')
            ->orderByDesc('total_qty')
            ->with('item.category')
            ->get();
    }

    public function map(mixed $row): array
    {
        return [
            $row->item->name,
            $row->item->category->name,
            $row->item->type === 'package' ? 'Paket' : 'Reguler',
            $row->item->unit,
            $row->total_orders,
            $row->total_qty,
        ];
    }

    public function headings(): array
    {
        return ['Barang', 'Kategori', 'Tipe', 'Satuan', 'Total Pesanan', 'Total Qty'];
    }

    public function title(): string
    {
        return 'Rekap Belanja';
    }
}
