<?php

use App\Models\Participant;
use App\Models\Saving;
use App\Models\SavingTransaction;
use App\Models\User;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('guest cannot access savings slip route', function () {
    $participant = Participant::factory()->create();

    auth()->logout();
    $this->get(route('participants.savings-slip', $participant))
        ->assertRedirect(route('login'));
});

test('admin can download savings slip pdf', function () {
    $participant = Participant::factory()->create();
    Saving::factory()->create(['participant_id' => $participant->id, 'balance' => 50000]);
    SavingTransaction::factory()->create(['participant_id' => $participant->id, 'type' => 'deposit', 'amount' => 50000]);

    $response = $this->get(route('participants.savings-slip', $participant));

    $response->assertOk();
    $response->assertHeader('content-type', 'application/pdf');
});

test('savings slip pdf filename contains participant name', function () {
    $participant = Participant::factory()->create(['name' => 'Budi Santoso']);
    Saving::factory()->create(['participant_id' => $participant->id, 'balance' => 0]);

    $response = $this->get(route('participants.savings-slip', $participant));

    $response->assertOk();
    expect($response->headers->get('content-disposition'))
        ->toContain('budi-santoso');
});

test('savings slip works for participant with no transactions', function () {
    $participant = Participant::factory()->create();

    $response = $this->get(route('participants.savings-slip', $participant));

    $response->assertOk();
    $response->assertHeader('content-type', 'application/pdf');
});
