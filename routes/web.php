<?php

use Illuminate\Support\Facades\Route;
use SistemasEel\SSOClient\Laravel\Http\Controllers\SSOController;

Route::livewire('/', 'views::index')->name('home');

Route::livewire('/admin', 'views::admin.index')->name('admin');

Route::livewire('/planejamento', 'views::planejamento.index')->name('planejamento');
Route::livewire('/planejamento/demandantes',            'views::planejamento.demandante.index')->name('demandantes');
Route::livewire('/planejamento/demandantes/create',     'views::planejamento.demandante.create')->name('demandantes.create');
Route::livewire('/planejamento/demandantes/{pca}/edit', 'views::planejamento.demandante.edit')->name('demandantes.edit');

/* Sobreescrever rotas de login senhaunica */
Route::get('/login',  [SSOController::class, 'login'])->name('login');
Route::get('/logout', [SSOController::class, 'logout'])->name('logout');

// Portal UI starter — arquivo gerenciado
require __DIR__.'/portal-ui-starter.php';
