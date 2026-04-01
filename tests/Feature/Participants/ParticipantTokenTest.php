<?php

use App\Models\Participant;
use App\Models\ParticipantToken;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('generateToken creates a token for the participant', function () {
    $participant = Participant::factory()->create();

    Livewire::test('pages::participants.index')
        ->call('openTokenModal', $participant->id)
        ->set('tokenDays', 7)
        ->call('generateToken');

    expect(ParticipantToken::where('participant_id', $participant->id)->exists())->toBeTrue();
});

test('generateToken sets generatedUrl with warga dashboard route', function () {
    $participant = Participant::factory()->create();

    Livewire::test('pages::participants.index')
        ->call('openTokenModal', $participant->id)
        ->set('tokenDays', 7)
        ->call('generateToken')
        ->assertSet('generatedUrl', fn ($url) => str_contains($url, '/w/'));
});

test('generateToken replaces existing token for participant', function () {
    $participant = Participant::factory()->create();
    $old = ParticipantToken::create([
        'participant_id' => $participant->id,
        'token' => str_repeat('a', 64),
        'expires_at' => now()->addDays(7),
    ]);

    Livewire::test('pages::participants.index')
        ->call('openTokenModal', $participant->id)
        ->set('tokenDays', 1)
        ->call('generateToken');

    expect(ParticipantToken::where('token', $old->token)->exists())->toBeFalse();
    expect(ParticipantToken::where('participant_id', $participant->id)->count())->toBe(1);
});

test('revokeToken deletes the participant token', function () {
    $participant = Participant::factory()->create();
    ParticipantToken::create([
        'participant_id' => $participant->id,
        'token' => str_repeat('b', 64),
        'expires_at' => now()->addDays(7),
    ]);

    Livewire::test('pages::participants.index')
        ->call('revokeToken', $participant->id);

    expect(ParticipantToken::where('participant_id', $participant->id)->exists())->toBeFalse();
});

test('generateToken validation rejects invalid duration', function () {
    $participant = Participant::factory()->create();

    Livewire::test('pages::participants.index')
        ->call('openTokenModal', $participant->id)
        ->set('tokenDays', 14)
        ->call('generateToken')
        ->assertHasErrors(['tokenDays']);
});
