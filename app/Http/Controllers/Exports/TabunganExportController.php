<?php

namespace App\Http\Controllers\Exports;

use App\Exports\TabunganExport;
use App\Http\Controllers\Controller;
use App\Models\Participant;
use App\Models\Saving;
use App\Models\SavingTransaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class TabunganExportController extends Controller
{
    public function pdf(Request $request): Response
    {
        $participants = Participant::with(['savingRecord', 'savingTransactions'])
            ->orderBy('name')
            ->get();

        $data = [
            'participants' => $participants,
            'totalBalance' => (int) Saving::sum('balance'),
            'totalDeposits' => (int) SavingTransaction::where('type', 'deposit')->sum('amount'),
            'totalWithdrawals' => (int) SavingTransaction::where('type', 'withdrawal')->sum('amount'),
        ];

        return Pdf::loadView('exports.tabungan', $data)
            ->setPaper('a4', 'portrait')
            ->download('laporan-tabungan.pdf');
    }

    public function excel(Request $request): BinaryFileResponse
    {
        return Excel::download(new TabunganExport, 'laporan-tabungan.xlsx');
    }
}
