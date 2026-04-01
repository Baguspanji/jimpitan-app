<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1f2937; padding: 24px; }
        h1 { font-size: 18px; font-weight: bold; margin-bottom: 4px; }
        .subtitle { color: #6b7280; margin-bottom: 20px; font-size: 11px; }
        .summary { display: flex; gap: 16px; margin-bottom: 24px; }
        .card { border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px 16px; flex: 1; }
        .card-label { color: #6b7280; font-size: 10px; margin-bottom: 4px; }
        .card-value { font-size: 16px; font-weight: bold; }
        .green { color: #16a34a; }
        table { width: 100%; border-collapse: collapse; }
        thead tr { background-color: #f9fafb; }
        th { text-align: left; padding: 9px 12px; font-weight: 600; font-size: 11px; border-bottom: 2px solid #e5e7eb; color: #374151; }
        td { padding: 8px 12px; border-bottom: 1px solid #f3f4f6; font-size: 11px; }
        tr:last-child td { border-bottom: none; }
        .tfoot-row { background-color: #f9fafb; }
        .tfoot-row td { font-weight: bold; border-top: 2px solid #e5e7eb; }
        .text-right { text-align: right; }
        .meta { margin-top: 32px; color: #9ca3af; font-size: 10px; text-align: right; }
    </style>
</head>
<body>
    <h1>Laporan Keuangan</h1>
    <p class="subtitle">
        {{ $period ? 'Periode: ' . $period : 'Semua Periode' }}
        &nbsp;&bull;&nbsp; Dicetak: {{ now()->isoFormat('D MMMM YYYY') }}
    </p>

    <div class="summary">
        <div class="card">
            <div class="card-label">Total Uang Masuk</div>
            <div class="card-value green">Rp {{ number_format($grandTotal, 0, ',', '.') }}</div>
        </div>
        <div class="card">
            <div class="card-label">Total Minggu Terbayar</div>
            <div class="card-value">{{ number_format($totalWeeksPaid) }} minggu</div>
        </div>
        <div class="card">
            <div class="card-label">Jumlah Order Aktif</div>
            <div class="card-value">{{ number_format($totalOrders) }} order</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Minggu ke-</th>
                <th>Jumlah Peserta Bayar</th>
                <th class="text-right">Total Uang Masuk</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($weeklyBreakdown as $row)
                <tr>
                    <td>Minggu {{ $row->week_number }}</td>
                    <td>{{ $row->participants_count }} peserta</td>
                    <td class="text-right green">Rp {{ number_format($row->total, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align:center; color:#9ca3af; padding: 20px;">Belum ada data angsuran.</td>
                </tr>
            @endforelse
        </tbody>
        @if ($weeklyBreakdown->isNotEmpty())
            <tfoot>
                <tr class="tfoot-row">
                    <td colspan="2">Total Keseluruhan</td>
                    <td class="text-right green">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        @endif
    </table>

    <p class="meta">SiJimbar &mdash; {{ config('app.name') }}</p>
</body>
</html>
