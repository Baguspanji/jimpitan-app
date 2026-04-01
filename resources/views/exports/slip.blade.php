<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Slip Jimpitan – {{ $participant->name }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1f2937; padding: 20px; }
        h1 { font-size: 15px; font-weight: bold; margin-bottom: 2px; }
        h2 { font-size: 12px; font-weight: bold; margin-bottom: 12px; }
        .subtitle { color: #6b7280; font-size: 10px; margin-bottom: 16px; }
        .section { margin-bottom: 14px; }
        .label { color: #6b7280; font-size: 9px; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px; }
        .value { font-size: 11px; }
        .grid-2 { width: 100%; border-collapse: collapse; }
        .grid-2 td { vertical-align: top; padding-right: 6px; }
        .grid-2 td:last-child { padding-right: 0; }
        .card { border: 1px solid #e5e7eb; border-radius: 6px; padding: 10px 12px; margin-bottom: 10px; }
        .card-title { font-size: 10px; font-weight: bold; color: #374151; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.05em; }
        table { width: 100%; border-collapse: collapse; }
        thead tr { background-color: #f9fafb; }
        th { text-align: left; padding: 6px 8px; font-weight: 600; font-size: 10px; border-bottom: 1px solid #e5e7eb; color: #374151; }
        td { padding: 5px 8px; border-bottom: 1px solid #f3f4f6; font-size: 10px; }
        .tfoot-row td { font-weight: bold; border-top: 1px solid #e5e7eb; background-color: #f9fafb; }
        .text-right { text-align: right; }
        .big-number { font-size: 14px; font-weight: bold; }
        .green { color: #16a34a; }
        .red { color: #dc2626; }
        .amber { color: #b45309; }
        .blue { color: #1d4ed8; }
        .progress-bar-bg { background-color: #e5e7eb; border-radius: 4px; height: 8px; width: 100%; margin-top: 4px; }
        .progress-bar-fill { background-color: #16a34a; border-radius: 4px; height: 8px; }
        .week-grid { width: 100%; border-collapse: separate; border-spacing: 2px; margin-top: 6px; }
        .week-dot { width: 15px; height: 15px; border-radius: 3px; font-size: 7px; font-weight: bold; text-align: center; padding: 3px 0; }
        .week-dot.paid { background-color: #dcfce7; color: #16a34a; }
        .week-dot.unpaid { background-color: #f3f4f6; color: #9ca3af; }
        .separator { border-top: 1px solid #e5e7eb; margin: 10px 0; }
        .footer { margin-top: 16px; color: #9ca3af; font-size: 9px; text-align: center; border-top: 1px solid #f3f4f6; padding-top: 8px; }
        .badge { display: inline-block; padding: 1px 6px; border-radius: 10px; font-size: 9px; font-weight: bold; }
        .badge-blue { background: #dbeafe; color: #1d4ed8; }
        .badge-zinc { background: #f4f4f5; color: #52525b; }
    </style>
</head>
<body>

    {{-- Header --}}
    <h1>Slip Jimpitan</h1>
    <p class="subtitle">Periode: {{ $order->period_name }} &nbsp;&bull;&nbsp; Dicetak: {{ now()->isoFormat('D MMMM YYYY') }}</p>

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
                    <div class="value">{{ $participant->phone }}</div>
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

    {{-- Daftar Barang --}}
    <div class="card">
        <div class="card-title">Daftar Barang / Paket</div>
        <table>
            <thead>
                <tr>
                    <th>Barang</th>
                    <th>Tipe</th>
                    <th>Qty</th>
                    <th class="text-right">Harga/Minggu</th>
                    <th class="text-right">Subtotal/Minggu</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->orderItems as $line)
                    <tr>
                        <td>{{ $line->item->name }}</td>
                        <td>
                            <span class="badge {{ $line->item->type === 'package' ? 'badge-blue' : 'badge-zinc' }}">
                                {{ $line->item->type === 'package' ? 'Paket' : 'Reguler' }}
                            </span>
                        </td>
                        <td>{{ $line->qty }}/{{ $line->item->unit }}</td>
                        <td class="text-right">Rp {{ number_format($line->item->weekly_price, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($line->item->weekly_price * $line->qty, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="tfoot-row">
                    <td colspan="4">Total per Minggu</td>
                    <td class="text-right green">Rp {{ number_format($weeklyTotal, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- Progress & Finansial --}}
    <table class="grid-2">
        <tr>
            <td style="width: 55%;">
            <div class="card">
            <div class="card-title">Progress Angsuran</div>

            <div style="margin-bottom: 6px;">
                <div class="label">Sudah Dibayar</div>
                <div class="big-number green">{{ $paidCount }} <span style="font-size:11px; font-weight:normal;">dari 45 minggu</span></div>
            </div>
            <div class="progress-bar-bg">
                <div class="progress-bar-fill" style="width: {{ round(($paidCount / 45) * 100) }}%;"></div>
            </div>
            <div style="font-size:9px; color:#6b7280; margin-top: 3px; text-align:right;">{{ round(($paidCount / 45) * 100) }}%</div>

            <div class="separator"></div>

            {{-- Week grid as a table (9 cols × 5 rows) --}}
            @php $weeks = range(1, 45); $chunks = array_chunk($weeks, 9); @endphp
            <table class="week-grid">
                @foreach ($chunks as $row)
                    <tr>
                        @foreach ($row as $w)
                            @php $isPaid = $order->installments->contains('week_number', $w); @endphp
                            <td class="week-dot {{ $isPaid ? 'paid' : 'unpaid' }}">{{ $w }}</td>
                        @endforeach
                        {{-- pad last row if needed --}}
                        @for ($p = count($row); $p < 9; $p++)
                            <td></td>
                        @endfor
                    </tr>
                @endforeach
            </table>
            </div>
            </td>

            <td style="width: 45%; vertical-align: top;">
            <div class="card">
                <div class="card-title">Rekap Finansial</div>
                <table>
                    <tr>
                        <td class="label">Total Order (45×)</td>
                        <td class="text-right">Rp {{ number_format($totalOrder, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="label green">Sudah Dibayar</td>
                        <td class="text-right green">Rp {{ number_format($totalPaid, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="label" style="color:#dc2626;">Sisa Tagihan</td>
                        <td class="text-right red">Rp {{ number_format($remaining, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>

            <div class="card">
                <div class="card-title">Saldo Tabungan</div>
                <div class="big-number blue">Rp {{ number_format($balance, 0, ',', '.') }}</div>
            </div>
            </td>
        </tr>
    </table>

    {{-- Footer --}}
    <p class="footer">SiJimbar &mdash; {{ config('app.name') }} &mdash; Slip ini dicetak otomatis oleh sistem</p>

</body>
</html>
