<?php

use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Participant;
use App\Models\User;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('guest cannot access slip route', function () {
    $participant = Participant::factory()->create();

    auth()->logout();
    $this->get(route('participants.slip', $participant))
        ->assertRedirect(route('login'));
});

test('admin can download slip pdf for participant with one order', function () {
    $participant = Participant::factory()->create();
    $item = Item::factory()->create(['weekly_price' => 10000]);
    $order = Order::factory()->for($participant)->create(['period_name' => '2025-2026']);
    OrderItem::factory()->create(['order_id' => $order->id, 'item_id' => $item->id, 'qty' => 1]);

    $response = $this->get(route('participants.slip.download', [$participant, $order]));

    $response->assertOk();
    $response->assertHeader('content-type', 'application/pdf');
});

test('slip pdf filename contains participant name', function () {
    $participant = Participant::factory()->create(['name' => 'Siti Fatimah']);
    $item = Item::factory()->create(['weekly_price' => 15000]);
    $order = Order::factory()->for($participant)->create(['period_name' => '2025-2026']);
    OrderItem::factory()->create(['order_id' => $order->id, 'item_id' => $item->id, 'qty' => 2]);

    $response = $this->get(route('participants.slip.download', [$participant, $order]));

    $response->assertOk();
    expect($response->headers->get('content-disposition'))
        ->toContain('siti-fatimah');
});

test('slip returns 404 when order does not belong to participant', function () {
    $participantA = Participant::factory()->create();
    $participantB = Participant::factory()->create();
    $order = Order::factory()->for($participantB)->create();

    $this->get(route('participants.slip.download', [$participantA, $order]))
        ->assertNotFound();
});

test('show redirects to download when participant has exactly one order', function () {
    $participant = Participant::factory()->create();
    $item = Item::factory()->create();
    $order = Order::factory()->for($participant)->create(['period_name' => '2025-2026']);
    OrderItem::factory()->create(['order_id' => $order->id, 'item_id' => $item->id, 'qty' => 1]);

    $this->get(route('participants.slip', $participant))
        ->assertRedirectToRoute('participants.slip.download', [$participant, $order]);
});
