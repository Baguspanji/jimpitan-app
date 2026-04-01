<?php

use App\Models\Category;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('guests are redirected to login', function () {
    auth()->logout();
    $this->get(route('categories.index'))->assertRedirect(route('login'));
});

test('authenticated users can view categories page', function () {
    $this->get(route('categories.index'))->assertOk();
});

test('categories are listed', function () {
    $category = Category::factory()->create();

    Livewire::test('pages::categories.index')
        ->assertSee($category->name);
});

test('a category can be created', function () {
    Livewire::test('pages::categories.index')
        ->call('openCreate')
        ->set('name', 'Sembako')
        ->call('save');

    expect(Category::where('name', 'Sembako')->exists())->toBeTrue();
});

test('category creation validates required name', function () {
    Livewire::test('pages::categories.index')
        ->call('openCreate')
        ->call('save')
        ->assertHasErrors(['name']);
});

test('a category can be edited', function () {
    $category = Category::factory()->create(['name' => 'Lama']);

    Livewire::test('pages::categories.index')
        ->call('openEdit', $category->id)
        ->set('name', 'Baru')
        ->call('save');

    expect($category->fresh()->name)->toBe('Baru');
});

test('a category can be deleted', function () {
    $category = Category::factory()->create();

    Livewire::test('pages::categories.index')
        ->call('confirmDelete', $category->id)
        ->call('delete');

    expect(Category::find($category->id))->toBeNull();
});
