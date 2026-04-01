<?php

namespace App\Http\Controllers\Exports;

use App\Exports\BelanjaExport;
use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class BelanjaExportController extends Controller
{
    public function pdf(Request $request): Response
    {
        $period = $request->string('period')->toString();

        $itemSummary = OrderItem::query()
            ->when($period, fn ($q) => $q->whereHas('order', fn ($o) => $o->where('period_name', $period)))
            ->selectRaw('item_id, SUM(qty) as total_qty, COUNT(DISTINCT order_id) as total_orders')
            ->groupBy('item_id')
            ->orderByDesc('total_qty')
            ->with('item.category')
            ->get();

        $data = [
            'period' => $period,
            'itemSummary' => $itemSummary,
            'grandTotalQty' => $itemSummary->sum('total_qty'),
        ];

        $filename = 'laporan-belanja'.($period ? '-'.$period : '').'.pdf';

        return Pdf::loadView('exports.belanja', $data)
            ->setPaper('a4', 'landscape')
            ->download($filename);
    }

    public function excel(Request $request): BinaryFileResponse
    {
        $period = $request->string('period')->toString();
        $filename = 'laporan-belanja'.($period ? '-'.$period : '').'.xlsx';

        return Excel::download(new BelanjaExport($period), $filename);
    }
}
