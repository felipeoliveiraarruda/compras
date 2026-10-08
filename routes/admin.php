<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () 
{
    Route::livewire('dashboard', 'admin::index')->name('dashboard');
    Route::livewire('/admin', 'views::admin.index')->name('admin');
    Route::livewire('importar', 'views::admin.importar')->name('importar');
});