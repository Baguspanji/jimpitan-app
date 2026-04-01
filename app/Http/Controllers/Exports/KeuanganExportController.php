<?php

namespace App\Http\Controllers\Exports;

use App\Exports\KeuanganExport;
use App\Http\Controllers\Controller;
use App\Models\Installment;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class KeuanganExportController extends Controller
{
    public function pdf(Request $request): Response
    {
        $period = $request->string('period')->toString();

        $data = [
            'period' => $period,
            'weeklyBreakdown' => Installment::query()
                ->when($period, fn ($q) => $q->whereHas('order', fn ($o) => $o->where('period_name', $period)))
                ->selectRaw('week_number, SUM(amount_paid) as total, COUNT(*) as participants_count')
                ->groupBy('week_number')
                ->orderBy('week_number')
                ->get(),
            'grandTotal' => (int) Installment::query()
                ->when($period, fn ($q) => $q->whereHas('order', fn ($o) => $o->where('period_name', $period)))
                ->sum('amount_paid'),
            'totalWeeksPaid' => (int) Installment::query()
                ->when($period, fn ($q) => $q->whereHas('order', fn ($o) => $o->where('period_name', $period)))
                ->count(),
            'totalOrders' => Order::query()
                ->when($period, fn ($q) => $q->where('period_name', $period))
                ->count(),
        ];

        $filename = 'laporan-keuangan'.($period ? '-'.$period : '').'.pdf';

        return Pdf::loadView('exports.keuangan', $data)
            ->setPaper('a4', 'portrait')
            ->download($filename);
    }

    public function excel(Request $request): BinaryFileResponse
    {
        $period = $request->string('period')->toString();
        $filename = 'laporan-keuangan'.($period ? '-'.$period : '').'.xlsx';

        return Excel::download(new KeuanganExport($period), $filename);
    }
}
