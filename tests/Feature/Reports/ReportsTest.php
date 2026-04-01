<?php

use App\Models\Installment;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Participant;
use App\Models\Saving;
use App\Models\SavingTransaction;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

// ─── Laporan Keuangan ───────────────────────────────────────────────────────

test('guests are redirected to login for keuangan report', function () {
    auth()->logout();
    $this->get(route('reports.keuangan'))->assertRedirect(route('login'));
});

test('authenticated users can view keuangan report', function () {
    $this->get(route('reports.keuangan'))->assertOk();
});

test('keuangan report shows installment totals per week', function () {
    $order = Order::factory()->for(Participant::factory())->create(['period_name' => '2025']);
    Installment::create(['order_id' => $order->id, 'week_number' => 1, 'amount_paid' => 50000, 'payment_date' => today()]);

    Livewire::test('pages::reports.keuangan')
        ->assertSee('50')
        ->assertSee('1');
});

test('keuangan report can filter by period', function () {
    $orderA = Order::factory()->for(Participant::factory())->create(['period_name' => '2025']);
    $orderB = Order::factory()->for(Participant::factory())->create(['period_name' => '2026']);
    Installment::create(['order_id' => $orderA->id, 'week_number' => 1, 'amount_paid' => 25000, 'payment_date' => today()]);
    Installment::create(['order_id' => $orderB->id, 'week_number' => 1, 'amount_paid' => 75000, 'payment_date' => today()]);

    Livewire::test('pages::reports.keuangan')
        ->set('filterPeriod', '2025')
        ->assertSee('25')
        ->assertDontSee('75.000');
});

// ─── Laporan Belanja ────────────────────────────────────────────────────────

test('guests are redirected to login for belanja report', function () {
    auth()->logout();
    $this->get(route('reports.belanja'))->assertRedirect(route('login'));
});

test('authenticated users can view belanja report', function () {
    $this->get(route('reports.belanja'))->assertOk();
});

test('belanja report shows item totals', function () {
    $item = Item::factory()->create(['name' => 'Beras 5kg']);
    $order = Order::factory()->for(Participant::factory())->create();
    OrderItem::create(['order_id' => $order->id, 'item_id' => $item->id, 'qty' => 3, 'price_per_week' => 50000]);

    Livewire::test('pages::reports.belanja')
        ->assertSee('Beras 5kg')
        ->assertSee('3');
});

test('belanja report can filter by period', function () {
    $item = Item::factory()->create(['name' => 'Teh Kotak']);
    $orderA = Order::factory()->for(Participant::factory())->create(['period_name' => '2025']);
    $orderB = Order::factory()->for(Participant::factory())->create(['period_name' => '2026']);
    OrderItem::create(['order_id' => $orderA->id, 'item_id' => $item->id, 'qty' => 2, 'price_per_week' => 10000]);
    OrderItem::create(['order_id' => $orderB->id, 'item_id' => $item->id, 'qty' => 5, 'price_per_week' => 10000]);

    Livewire::test('pages::reports.belanja')
        ->set('filterPeriod', '2025')
        ->assertSee('2');
});

// ─── Laporan Tabungan ───────────────────────────────────────────────────────

test('guests are redirected to login for tabungan report', function () {
    auth()->logout();
    $this->get(route('reports.tabungan'))->assertRedirect(route('login'));
});

test('authenticated users can view tabungan report', function () {
    $this->get(route('reports.tabungan'))->assertOk();
});

test('tabungan report lists participants with their balance', function () {
    $participant = Participant::factory()->create(['name' => 'Rudi Santoso']);
    Saving::create(['participant_id' => $participant->id, 'balance' => 150000]);

    Livewire::test('pages::reports.tabungan')
        ->assertSee('Rudi Santoso')
        ->assertSee('150');
});

test('tabungan report search filters by participant name', function () {
    $matchP = Participant::factory()->create(['name' => 'Ani Rahayu']);
    $otherP = Participant::factory()->create(['name' => 'Budi Kusumo']);

    Livewire::test('pages::reports.tabungan')
        ->set('search', 'Ani')
        ->assertSee('Ani Rahayu')
        ->assertDontSee('Budi Kusumo');
});

test('tabungan report shows total deposits and withdrawals', function () {
    $participant = Participant::factory()->create();
    $user = User::first();
    Saving::create(['participant_id' => $participant->id, 'balance' => 70000]);
    SavingTransaction::create(['participant_id' => $participant->id, 'type' => 'deposit', 'amount' => 100000, 'transaction_date' => today(), 'created_by' => $user->id, 'notes' => null]);
    SavingTransaction::create(['participant_id' => $participant->id, 'type' => 'withdrawal', 'amount' => 30000, 'transaction_date' => today(), 'created_by' => $user->id, 'notes' => null]);

    Livewire::test('pages::reports.tabungan')
        ->assertSee('100')
        ->assertSee('30');
});
