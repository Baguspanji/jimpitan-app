<?php

use App\Models\Participant;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('guests are redirected to login', function () {
    auth()->logout();
    $this->get(route('participants.index'))->assertRedirect(route('login'));
});

test('authenticated users can view participants page', function () {
    $this->get(route('participants.index'))->assertOk();
});

test('participants are listed', function () {
    $participants = Participant::factory()->count(3)->create();

    Livewire::test('pages::participants.index')
        ->assertSee($participants->first()->name);
});

test('participants can be searched by name', function () {
    Participant::factory()->create(['name' => 'Siti Aminah']);
    Participant::factory()->create(['name' => 'Budi Santoso']);

    Livewire::test('pages::participants.index')
        ->set('search', 'Siti')
        ->assertSee('Siti Aminah')
        ->assertDontSee('Budi Santoso');
});

test('a participant can be created', function () {
    Livewire::test('pages::participants.index')
        ->call('openCreate')
        ->set('name', 'Dewi Lestari')
        ->set('phone', '08123456789')
        ->set('address', 'RT 02/RW 05')
        ->call('save');

    expect(Participant::where('name', 'Dewi Lestari')->exists())->toBeTrue();
});

test('participant creation validates required fields', function () {
    Livewire::test('pages::participants.index')
        ->call('openCreate')
        ->call('save')
        ->assertHasErrors(['name', 'phone']);
});

test('a participant can be edited', function () {
    $participant = Participant::factory()->create(['name' => 'Nama Lama']);

    Livewire::test('pages::participants.index')
        ->call('openEdit', $participant->id)
        ->set('name', 'Nama Baru')
        ->call('save');

    expect($participant->fresh()->name)->toBe('Nama Baru');
});

test('a participant can be deleted', function () {
    $participant = Participant::factory()->create();

    Livewire::test('pages::participants.index')
        ->call('confirmDelete', $participant->id)
        ->call('delete');

    expect(Participant::find($participant->id))->toBeNull();
});
