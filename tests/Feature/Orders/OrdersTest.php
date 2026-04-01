<?php

use App\Models\Item;
use App\Models\Order;
use App\Models\Participant;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('guests are redirected to login', function () {
    auth()->logout();
    $this->get(route('orders.index'))->assertRedirect(route('login'));
});

test('authenticated users can view orders page', function () {
    $this->get(route('orders.index'))->assertOk();
});

test('orders are listed', function () {
    $order = Order::factory()->for(Participant::factory())->create();

    Livewire::test('pages::orders.index')
        ->assertSee($order->participant->name);
});

test('an order can be created', function () {
    $participant = Participant::factory()->create();
    $item = Item::factory()->create();

    Livewire::test('pages::orders.index')
        ->call('openCreate')
        ->set('participant_id', $participant->id)
        ->set('period_name', '2025/2026')
        ->set('orderLines', [['item_id' => (string) $item->id, 'qty' => '2']])
        ->call('save');

    expect(Order::where('participant_id', $participant->id)->where('period_name', '2025/2026')->exists())
        ->toBeTrue();
});

test('order creation validates required fields', function () {
    Livewire::test('pages::orders.index')
        ->call('openCreate')
        ->set('orderLines', [])
        ->call('save')
        ->assertHasErrors(['participant_id', 'period_name', 'orderLines']);
});

test('an order can be deleted', function () {
    $order = Order::factory()->for(Participant::factory())->create();

    Livewire::test('pages::orders.index')
        ->call('confirmDelete', $order->id)
        ->call('delete');

    expect(Order::find($order->id))->toBeNull();
});
