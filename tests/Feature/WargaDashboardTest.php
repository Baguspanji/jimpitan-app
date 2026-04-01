<?php

use App\Models\Participant;
use App\Models\ParticipantToken;

test('valid token shows warga dashboard', function () {
    $participant = Participant::factory()->create();
    $token = ParticipantToken::create([
        'participant_id' => $participant->id,
        'token' => str_repeat('c', 64),
        'expires_at' => now()->addDays(7),
    ]);

    $this->get(route('warga.dashboard', $token->token))
        ->assertOk()
        ->assertViewIs('pages.warga.dashboard');
});

test('expired token shows expired view', function () {
    $participant = Participant::factory()->create();
    $token = ParticipantToken::create([
        'participant_id' => $participant->id,
        'token' => str_repeat('d', 64),
        'expires_at' => now()->subDay(),
    ]);

    $this->get(route('warga.dashboard', $token->token))
        ->assertOk()
        ->assertViewIs('pages.warga.expired');
});

test('invalid token returns 404', function () {
    $this->get(route('warga.dashboard', 'invalid-token'))
        ->assertNotFound();
});

test('dashboard displays participant name', function () {
    $participant = Participant::factory()->create(['name' => 'Dewi Rahayu']);
    $token = ParticipantToken::create([
        'participant_id' => $participant->id,
        'token' => str_repeat('e', 64),
        'expires_at' => now()->addDays(7),
    ]);

    $this->get(route('warga.dashboard', $token->token))
        ->assertOk()
        ->assertSee('Dewi Rahayu');
});

test('dashboard masks participant phone number', function () {
    $participant = Participant::factory()->create(['phone' => '081234567890']);
    $token = ParticipantToken::create([
        'participant_id' => $participant->id,
        'token' => str_repeat('f', 64),
        'expires_at' => now()->addDays(7),
    ]);

    $response = $this->get(route('warga.dashboard', $token->token));

    $response->assertOk();
    $response->assertSee('0812****7890');
    $response->assertDontSee('081234567890');
});
