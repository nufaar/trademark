<?php

use App\Http\Controllers\UserController;
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
    Route::view('profile', 'profile')
        ->name('profile');
    Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
        Route::view('profile', 'users.profile')
            ->name('profile');
        Route::middleware(['verified'])->group(function () {
            Route::get('/', function () {
                return view('users.index');
            })->name('index');
            Route::get('create', function () {
                return view('users.create');
            })->name('create');
            Route::get('{user}/edit', [UserController::class, 'edit'])->name('edit');
        });
    });
});

require __DIR__ . '/auth.php';
