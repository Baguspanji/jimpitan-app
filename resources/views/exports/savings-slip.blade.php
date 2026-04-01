<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Slip Tabungan – {{ $participant->name }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1f2937; padding: 20px; }
        h1 { font-size: 15px; font-weight: bold; margin-bottom: 2px; }
        .subtitle { color: #6b7280; font-size: 10px; margin-bottom: 16px; }
        .label { color: #6b7280; font-size: 9px; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px; }
        .value { font-size: 11px; }
        .card { border: 1px solid #e5e7eb; border-radius: 6px; padding: 10px 12px; margin-bottom: 10px; }
        .card-title { font-size: 10px; font-weight: bold; color: #374151; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.05em; }
        .grid-2 { width: 100%; border-collapse: collapse; }
        .grid-2 td { vertical-align: top; padding-right: 6px; }
        .grid-2 td:last-child { padding-right: 0; }
        .summary-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .summary-table td { vertical-align: top; padding: 0 4px; }
        .summary-table td:first-child { padding-left: 0; }
        .summary-table td:last-child { padding-right: 0; }
        .summary-card { border: 1px solid #e5e7eb; border-radius: 6px; padding: 10px 12px; }
        .big-number { font-size: 13px; font-weight: bold; }
        .green { color: #16a34a; }
        .red { color: #dc2626; }
        .blue { color: #1d4ed8; }
        table { width: 100%; border-collapse: collapse; }
        thead tr { background-color: #f9fafb; }
        th { text-align: left; padding: 6px 8px; font-weight: 600; font-size: 10px; border-bottom: 1px solid #e5e7eb; color: #374151; }
        td { padding: 5px 8px; border-bottom: 1px solid #f3f4f6; font-size: 10px; }
        .tfoot-row td { font-weight: bold; border-top: 1px solid #e5e7eb; background-color: #f9fafb; }
        .text-right { text-align: right; }
        .footer { margin-top: 16px; color: #9ca3af; font-size: 9px; text-align: center; border-top: 1px solid #f3f4f6; padding-top: 8px; }
        .empty-note { color: #9ca3af; font-size: 10px; text-align: center; padding: 12px 0; }
    </style>
</head>
<body>

    {{-- Header --}}
    <h1>Slip Tabungan</h1>
    <p class="subtitle">Dicetak: {{ now()->isoFormat('D MMMM YYYY, HH:mm') }}</p>

    {{-- Identitas --}}
    <div class="card">
        <div class="card-title">Identitas Peserta</div>
        <table class="grid-2">
            <tr>
                <td style="width: 50%;">
                    <div class="label">Nama Lengkap</div>
                    <div class="value" style="font-weight: bold;">{{ $participant->name }}</div>
                </td>
                <td style="width: 50%;">
                    <div class="label">No. HP</div>
                    <div class="value">{{ $participant->phone ?? '-' }}</div>
                </td>
            </tr>
        </table>
        @if ($participant->address)
            <div style="margin-top: 6px;">
                <div class="label">Alamat</div>
                <div class="value">{{ $participant->address }}</div>
            </div>
        @endif
    </div>

    {{-- Summary --}}
    <table class="summary-table">
        <tr>
            <td style="width: 33.33%;">
                <div class="summary-card">
                    <div class="label">Total Setor</div>
                    <div class="big-number green">Rp {{ number_format($totalDeposits, 0, ',', '.') }}</div>
                </div>
            </td>
            <td style="width: 33.33%;">
                <div class="summary-card">
                    <div class="label">Total Tarik</div>
                    <div class="big-number red">Rp {{ number_format($totalWithdrawals, 0, ',', '.') }}</div>
                </div>
            </td>
            <td style="width: 33.33%;">
                <div class="summary-card">
                    <div class="label">Saldo Saat Ini</div>
                    <div class="big-number blue">Rp {{ number_format($balance, 0, ',', '.') }}</div>
                </div>
            </td>
        </tr>
    </table>

    {{-- Transaction History --}}
    <div class="card">
        <div class="card-title">Riwayat Transaksi</div>
        @if ($transactions->isEmpty())
            <div class="empty-note">Belum ada transaksi.</div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Tipe</th>
                        <th>Keterangan</th>
                        <th class="text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $tx)
                        <tr>
                            <td>{{ $tx->transaction_date->isoFormat('D MMM YYYY') }}</td>
                            <td>
                                @if ($tx->type === 'deposit')
                                    <span class="green" style="font-weight: bold;">Setor</span>
                                @else
                                    <span class="red" style="font-weight: bold;">Tarik</span>
                                @endif
                            </td>
                            <td>{{ $tx->note ?? '-' }}</td>
                            <td class="text-right {{ $tx->type === 'deposit' ? 'green' : 'red' }}">
                                {{ $tx->type === 'withdrawal' ? '-' : '+' }}Rp {{ number_format($tx->amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="tfoot-row">
                        <td colspan="3">Saldo Akhir</td>
                        <td class="text-right blue">Rp {{ number_format($balance, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        @endif
    </div>

    {{-- Footer --}}
    <p class="footer">SiJimbar &mdash; {{ config('app.name') }} &mdash; Slip ini dicetak otomatis oleh sistem</p>

</body>
</html>
