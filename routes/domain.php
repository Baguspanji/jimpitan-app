<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::livewire('participants', 'pages::participants.index')->name('participants.index');

    Route::livewire('categories', 'pages::categories.index')->name('categories.index');
});
