<?php

namespace App\Http\Controllers\Exports;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Participant;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class SlipExportController extends Controller
{
    /**
     * Show period picker if participant has multiple orders.
     * Redirects directly to download if only one order exists.
     */
    public function show(Participant $participant): Response|RedirectResponse
    {
        $orders = $participant->orders()->orderByDesc('period_name')->get();

        if ($orders->count() === 1) {
            return redirect()->route('participants.slip.download', [$participant, $orders->first()]);
        }

        return response()->view('pages.slip-picker', compact('participant', 'orders'));
    }

    /**
     * Generate and download the participant slip PDF.
     */
    public function download(Participant $participant, Order $order): Response
    {
        abort_unless($order->participant_id === $participant->id, 404);

        $order->load(['participant', 'orderItems.item', 'installments']);
        $participant->load('savingRecord');

        $weeklyTotal = $order->weeklyTotal();
        $paidCount = $order->installments->count();
        $totalPaid = (int) $order->installments->sum('amount_paid');
        $totalOrder = 45 * $weeklyTotal;
        $remaining = $totalOrder - $totalPaid;

        $filename = 'slip-'.str($participant->name)->slug().'-'.$order->period_name.'.pdf';

        return Pdf::loadView('exports.slip', [
            'participant' => $participant,
            'order' => $order,
            'weeklyTotal' => $weeklyTotal,
            'paidCount' => $paidCount,
            'totalPaid' => $totalPaid,
            'totalOrder' => $totalOrder,
            'remaining' => $remaining,
            'balance' => $participant->savingRecord?->balance ?? 0,
        ])
            ->setPaper('a5', 'portrait')
            ->download($filename);
    }
}
