<?php

use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('guests are redirected to login', function () {
    auth()->logout();
    $this->get(route('items.index'))->assertRedirect(route('login'));
});

test('authenticated users can view items page', function () {
    $this->get(route('items.index'))->assertOk();
});

test('items are listed', function () {
    $item = Item::factory()->create();

    Livewire::test('pages::items.index')
        ->assertSee($item->name);
});

test('an item can be created', function () {
    $category = Category::factory()->create();

    Livewire::test('pages::items.index')
        ->call('openCreate')
        ->set('category_id', $category->id)
        ->set('name', 'Beras 5Kg')
        ->set('unit', 'Karung')
        ->set('weekly_price', '50000')
        ->set('type', 'regular')
        ->call('save');

    expect(Item::where('name', 'Beras 5Kg')->exists())->toBeTrue();
});

test('item creation validates required fields', function () {
    Livewire::test('pages::items.index')
        ->call('openCreate')
        ->call('save')
        ->assertHasErrors(['category_id', 'name', 'unit', 'weekly_price']);
});

test('an item can be edited', function () {
    $item = Item::factory()->create(['name' => 'Lama']);

    Livewire::test('pages::items.index')
        ->call('openEdit', $item->id)
        ->set('name', 'Baru')
        ->call('save');

    expect($item->fresh()->name)->toBe('Baru');
});

test('an item can be deleted', function () {
    $item = Item::factory()->create();

    Livewire::test('pages::items.index')
        ->call('confirmDelete', $item->id)
        ->call('delete');

    expect(Item::find($item->id))->toBeNull();
});

test('items can be filtered by category', function () {
    $catA = Category::factory()->create();
    $catB = Category::factory()->create();
    $itemA = Item::factory()->create(['category_id' => $catA->id, 'name' => 'Alpha Item']);
    $itemB = Item::factory()->create(['category_id' => $catB->id, 'name' => 'Beta Item']);

    Livewire::test('pages::items.index')
        ->set('filterCategory', $catA->id)
        ->assertSee($itemA->name)
        ->assertDontSee($itemB->name);
});

test('items can be filtered by type', function () {
    $regular = Item::factory()->create(['type' => 'regular', 'name' => 'Regular Item']);
    $package = Item::factory()->package()->create(['name' => 'Package Item']);

    Livewire::test('pages::items.index')
        ->set('filterType', 'package')
        ->assertSee($package->name)
        ->assertDontSee($regular->name);
});
