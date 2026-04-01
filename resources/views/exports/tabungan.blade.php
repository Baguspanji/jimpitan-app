<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Tabungan</title>
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
        .red { color: #dc2626; }
        table { width: 100%; border-collapse: collapse; }
        thead tr { background-color: #f9fafb; }
        th { text-align: left; padding: 9px 12px; font-weight: 600; font-size: 11px; border-bottom: 2px solid #e5e7eb; color: #374151; }
        td { padding: 8px 12px; border-bottom: 1px solid #f3f4f6; font-size: 11px; }
        tr:last-child td { border-bottom: none; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: 600; }
        .badge-green { background: #dcfce7; color: #15803d; }
        .badge-zinc { background: #f4f4f5; color: #3f3f46; }
        .tfoot-row { background-color: #f9fafb; }
        .tfoot-row td { font-weight: bold; border-top: 2px solid #e5e7eb; }
        .meta { margin-top: 32px; color: #9ca3af; font-size: 10px; text-align: right; }
    </style>
</head>
<body>
    <h1>Laporan Tabungan</h1>
    <p class="subtitle">Dicetak: {{ now()->isoFormat('D MMMM YYYY') }}</p>

    <div class="summary">
        <div class="card">
            <div class="card-label">Total Saldo Aktif</div>
            <div class="card-value green">Rp {{ number_format($totalBalance, 0, ',', '.') }}</div>
        </div>
        <div class="card">
            <div class="card-label">Total Setoran</div>
            <div class="card-value">Rp {{ number_format($totalDeposits, 0, ',', '.') }}</div>
        </div>
        <div class="card">
            <div class="card-label">Total Penarikan</div>
            <div class="card-value red">Rp {{ number_format($totalWithdrawals, 0, ',', '.') }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Peserta</th>
                <th>Total Setoran</th>
                <th>Total Penarikan</th>
                <th>Saldo</th>
                <th>Jml Transaksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($participants as $i => $participant)
                @php
                    $deposits = $participant->savingTransactions->where('type', 'deposit')->sum('amount');
                    $withdrawals = $participant->savingTransactions->where('type', 'withdrawal')->sum('amount');
                    $balance = $participant->savingRecord?->balance ?? 0;
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td><strong>{{ $participant->name }}</strong></td>
                    <td class="green">Rp {{ number_format($deposits, 0, ',', '.') }}</td>
                    <td class="red">Rp {{ number_format($withdrawals, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge {{ $balance > 0 ? 'badge-green' : 'badge-zinc' }}">
                            Rp {{ number_format($balance, 0, ',', '.') }}
                        </span>
                    </td>
                    <td>{{ $participant->savingTransactions->count() }} transaksi</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center; color:#9ca3af; padding: 20px;">Tidak ada data peserta.</td>
                </tr>
            @endforelse
        </tbody>
        @if ($participants->isNotEmpty())
            <tfoot>
                <tr class="tfoot-row">
                    <td colspan="2">Total</td>
                    <td class="green">Rp {{ number_format($totalDeposits, 0, ',', '.') }}</td>
                    <td class="red">Rp {{ number_format($totalWithdrawals, 0, ',', '.') }}</td>
                    <td class="green">Rp {{ number_format($totalBalance, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
        @endif
    </table>

    <p class="meta">SiJimbar &mdash; {{ config('app.name') }}</p>
</body>
</html>
