<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\TrademarkController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::get('admin', function () {
    return view('layouts.admin');
})->middleware(['auth', 'verified', 'role:admin'])->name('test');


Route::middleware(['auth', 'auth.session'])->group(function () {
    Route::view('user/profile', 'users.profile')
        ->name('user.profile');
    Route::middleware(['verified'])->group(function () {

        // route user
        Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
            Route::get('/', function () {
                return view('users.index');
            })->name('index');
            Route::get('create', function () {
                return view('users.create');
            })->name('create');
            Route::get('{user}/edit', [UserController::class, 'edit'])->name('edit');
        });

        // route trademark
        Route::group(['prefix' => 'trademark', 'as' => 'trademark.'], function () {
            Route::get('/', function () {
                return view('trademarks.index');
            })->name('index');
            Route::get('create', function () {
                return view('trademarks.create');
            })->name('create');
            Route::get('{trademark}/edit', [TrademarkController::class, 'edit'])->name('edit');
            Route::get('{trademark}/show', [TrademarkController::class, 'show'])->name('show');
        });

        // route artikel
        Route::group(['prefix' => 'artikel', 'as' => 'artikel.'], function () {
            Route::get('/', function () {
                return view('artikels.index');
            })->name('index');
            Route::get('create', function () {
                return view('artikels.create');
            })->name('create');
            Route::get('{artikel}/edit', [ArticleController::class, 'edit'])->name('edit');
            Route::get('{artikel}', [ArticleController::class, 'show'])->name('show');
        });
    });
});

require __DIR__ . '/auth.php';
