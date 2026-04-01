<?php

use App\Models\Installment;
use App\Models\Order;
use App\Models\Participant;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('guests are redirected to login', function () {
    auth()->logout();
    $this->get(route('installments.index'))->assertRedirect(route('login'));
});

test('authenticated users can view installments page', function () {
    $this->get(route('installments.index'))->assertOk();
});

test('an installment week can be marked as paid', function () {
    $order = Order::factory()->for(Participant::factory())->create();

    Livewire::test('pages::installments.index')
        ->set('filterOrder', (string) $order->id)
        ->call('toggleInstallment', $order->id, 1);

    expect(Installment::where('order_id', $order->id)->where('week_number', 1)->exists())->toBeTrue();
});

test('an installment week can be toggled off', function () {
    $order = Order::factory()->for(Participant::factory())->create();
    Installment::create([
        'order_id' => $order->id,
        'week_number' => 2,
        'amount_paid' => 0,
        'payment_date' => now()->toDateString(),
    ]);

    Livewire::test('pages::installments.index')
        ->set('filterOrder', (string) $order->id)
        ->call('toggleInstallment', $order->id, 2);

    expect(Installment::where('order_id', $order->id)->where('week_number', 2)->exists())->toBeFalse();
});
