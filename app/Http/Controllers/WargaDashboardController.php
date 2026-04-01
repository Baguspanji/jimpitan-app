<?php

namespace App\Http\Controllers;

use App\Models\ParticipantToken;
use Illuminate\Http\Response;

class WargaDashboardController
{
    public function show(string $token): Response
    {
        $participantToken = ParticipantToken::where('token', $token)->firstOrFail();

        if ($participantToken->isExpired()) {
            return response()->view('pages.warga.expired', [], 200);
        }

        $participant = $participantToken->participant->load([
            'orders.orderItems.item',
            'orders.installments',
            'savingRecord',
            'savingTransactions' => fn ($q) => $q->latest('transaction_date')->limit(5),
        ]);

        $maskedPhone = substr($participant->phone, 0, 4).'****'.substr($participant->phone, -4);
        $expiresAt = $participantToken->expires_at;

        return response()->view('pages.warga.dashboard', compact('participant', 'maskedPhone', 'expiresAt'));
    }
}
