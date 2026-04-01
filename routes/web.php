<?php

use App\Http\Controllers\WargaDashboardController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'landing')->name('home');

Route::get('/w/{token}', [WargaDashboardController::class, 'show'])->name('warga.dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::livewire('dashboard', 'pages::dashboard.index')->name('dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/domain.php';
