<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use PortalSistemas\SSOClient\Laravel\Http\Controllers\SSOController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/starter', function () {
    return view('welcome');
});

Route::get('/', [HomeController::class, 'index']);

// Permite usar Gate::check('user')na view 404
Route::fallback(function(){
    return view('errors.404');
 });

Route::get('/login',    [SSOController::class, 'login'])->name('login');
Route::get('/logout',   [SSOController::class, 'logout'])->name('logout');


 require __DIR__.'/admin.php';
