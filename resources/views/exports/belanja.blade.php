<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Belanja (Kulakan)</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1f2937; padding: 24px; }
        h1 { font-size: 18px; font-weight: bold; margin-bottom: 4px; }
        .subtitle { color: #6b7280; margin-bottom: 20px; font-size: 11px; }
        .summary { display: flex; gap: 16px; margin-bottom: 24px; }
        .card { border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px 16px; flex: 1; }
        .card-label { color: #6b7280; font-size: 10px; margin-bottom: 4px; }
        .card-value { font-size: 16px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; }
        thead tr { background-color: #f9fafb; }
        th { text-align: left; padding: 9px 12px; font-weight: 600; font-size: 11px; border-bottom: 2px solid #e5e7eb; color: #374151; }
        td { padding: 8px 12px; border-bottom: 1px solid #f3f4f6; font-size: 11px; }
        tr:last-child td { border-bottom: none; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: 600; }
        .badge-blue { background: #dbeafe; color: #1d4ed8; }
        .badge-zinc { background: #f4f4f5; color: #3f3f46; }
        .badge-amber { background: #fef3c7; color: #b45309; }
        .tfoot-row { background-color: #f9fafb; }
        .tfoot-row td { font-weight: bold; border-top: 2px solid #e5e7eb; }
        .meta { margin-top: 32px; color: #9ca3af; font-size: 10px; text-align: right; }
    </style>
</head>
<body>
    <h1>Laporan Belanja (Kulakan)</h1>
    <p class="subtitle">
        {{ $period ? 'Periode: ' . $period : 'Semua Periode' }}
        &nbsp;&bull;&nbsp; Dicetak: {{ now()->isoFormat('D MMMM YYYY') }}
    </p>

    <div class="summary">
        <div class="card">
            <div class="card-label">Total Jenis Barang</div>
            <div class="card-value">{{ $itemSummary->count() }} jenis</div>
        </div>
        <div class="card">
            <div class="card-label">Total Kuantitas Keseluruhan</div>
            <div class="card-value">{{ number_format($grandTotalQty) }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Barang</th>
                <th>Kategori</th>
                <th>Tipe</th>
                <th>Satuan</th>
                <th>Total Pesanan</th>
                <th>Total Qty</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($itemSummary as $row)
                <tr>
                    <td><strong>{{ $row->item->name }}</strong></td>
                    <td>{{ $row->item->category->name }}</td>
                    <td>
                        <span class="badge {{ $row->item->type === 'package' ? 'badge-blue' : 'badge-zinc' }}">
                            {{ $row->item->type === 'package' ? 'Paket' : 'Reguler' }}
                        </span>
                    </td>
                    <td>{{ $row->item->unit }}</td>
                    <td>{{ $row->total_orders }} peserta</td>
                    <td>
                        <span class="badge badge-amber">{{ number_format($row->total_qty) }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center; color:#9ca3af; padding: 20px;">Belum ada data order.</td>
                </tr>
            @endforelse
        </tbody>
        @if ($itemSummary->isNotEmpty())
            <tfoot>
                <tr class="tfoot-row">
                    <td colspan="4">Total</td>
                    <td></td>
                    <td>{{ number_format($grandTotalQty) }}</td>
                </tr>
            </tfoot>
        @endif
    </table>

    <p class="meta">SiJimbar &mdash; {{ config('app.name') }}</p>
</body>
</html>
