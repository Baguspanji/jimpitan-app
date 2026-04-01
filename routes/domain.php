<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::livewire('participants', 'pages::participants.index')->name('participants.index');

    Route::livewire('categories', 'pages::categories.index')->name('categories.index');

    Route::livewire('items', 'pages::items.index')->name('items.index');

    Route::livewire('orders', 'pages::orders.index')->name('orders.index');

    Route::livewire('installments', 'pages::installments.index')->name('installments.index');

    Route::livewire('savings', 'pages::savings.index')->name('savings.index');

    Route::livewire('savings/{participant}', 'pages::savings.show')->name('savings.show');
});
