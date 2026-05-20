<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\IndexController;
use App\Http\Controllers\Admin\ImportacaoController;

Route::middleware(['auth','verified'])->group(function () 
{   
    Route::group(['prefix' => 'admin'], function()
    {
        Route::resource('/',            IndexController::class);
        Route::resource('importacao',   ImportacaoController::class);
    });
});
