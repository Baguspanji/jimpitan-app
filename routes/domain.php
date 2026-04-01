<?php

use App\Http\Controllers\Exports\BelanjaExportController;
use App\Http\Controllers\Exports\KeuanganExportController;
use App\Http\Controllers\Exports\TabunganExportController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::livewire('participants', 'pages::participants.index')->name('participants.index');

    Route::livewire('categories', 'pages::categories.index')->name('categories.index');

    Route::livewire('items', 'pages::items.index')->name('items.index');

    Route::livewire('orders', 'pages::orders.index')->name('orders.index');

    Route::livewire('installments', 'pages::installments.index')->name('installments.index');

    Route::livewire('savings', 'pages::savings.index')->name('savings.index');

    Route::livewire('savings/{participant}', 'pages::savings.show')->name('savings.show');

    Route::livewire('reports/keuangan', 'pages::reports.keuangan')->name('reports.keuangan');
    Route::get('reports/keuangan/export/pdf', [KeuanganExportController::class, 'pdf'])->name('reports.keuangan.pdf');
    Route::get('reports/keuangan/export/excel', [KeuanganExportController::class, 'excel'])->name('reports.keuangan.excel');

    Route::livewire('reports/belanja', 'pages::reports.belanja')->name('reports.belanja');
    Route::get('reports/belanja/export/pdf', [BelanjaExportController::class, 'pdf'])->name('reports.belanja.pdf');
    Route::get('reports/belanja/export/excel', [BelanjaExportController::class, 'excel'])->name('reports.belanja.excel');

    Route::livewire('reports/tabungan', 'pages::reports.tabungan')->name('reports.tabungan');
    Route::get('reports/tabungan/export/pdf', [TabunganExportController::class, 'pdf'])->name('reports.tabungan.pdf');
    Route::get('reports/tabungan/export/excel', [TabunganExportController::class, 'excel'])->name('reports.tabungan.excel');
});
