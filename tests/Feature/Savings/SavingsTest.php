<?php

use App\Models\Participant;
use App\Models\Saving;
use App\Models\SavingTransaction;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('guests are redirected to login for savings index', function () {
    auth()->logout();
    $this->get(route('savings.index'))->assertRedirect(route('login'));
});

test('guests are redirected to login for savings show', function () {
    $participant = Participant::factory()->create();
    auth()->logout();
    $this->get(route('savings.show', $participant))->assertRedirect(route('login'));
});

test('authenticated users can view savings list', function () {
    $this->get(route('savings.index'))->assertOk();
});

test('participants are listed with their balance', function () {
    $participant = Participant::factory()->create();
    Saving::create(['participant_id' => $participant->id, 'balance' => 75000]);

    Livewire::test('pages::savings.index')
        ->assertSee($participant->name);
});

test('authenticated users can view savings show page', function () {
    $participant = Participant::factory()->create();
    $this->get(route('savings.show', $participant))->assertOk();
});

test('a deposit increases the balance', function () {
    $participant = Participant::factory()->create();

    Livewire::test('pages::savings.show', ['participant' => $participant])
        ->call('openForm', 'deposit')
        ->set('txAmount', '50000')
        ->call('save');

    expect(Saving::where('participant_id', $participant->id)->value('balance'))->toBe(50000);
    expect(SavingTransaction::where('participant_id', $participant->id)->where('type', 'deposit')->exists())->toBeTrue();
});

test('a withdrawal decreases the balance', function () {
    $participant = Participant::factory()->create();
    Saving::create(['participant_id' => $participant->id, 'balance' => 100000]);

    Livewire::test('pages::savings.show', ['participant' => $participant])
        ->call('openForm', 'withdrawal')
        ->set('txAmount', '30000')
        ->call('save');

    expect(Saving::where('participant_id', $participant->id)->value('balance'))->toBe(70000);
});

test('withdrawal validates insufficient balance', function () {
    $participant = Participant::factory()->create();
    Saving::create(['participant_id' => $participant->id, 'balance' => 10000]);

    Livewire::test('pages::savings.show', ['participant' => $participant])
        ->call('openForm', 'withdrawal')
        ->set('txAmount', '50000')
        ->call('save')
        ->assertHasErrors(['txAmount']);

    expect(Saving::where('participant_id', $participant->id)->value('balance'))->toBe(10000);
});
