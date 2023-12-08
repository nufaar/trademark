<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
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

Route::view('/', 'front.index')->name('front.index');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::get('admin', function () {
    return view('layouts.admin');
})->middleware(['auth', 'verified'])->name('test');


Route::middleware(['auth'])->group(function () {
    Route::view('user/profile', 'users.profile')
        ->name('user.profile');
    Route::middleware(['verified'])->group(function () {

        // route user
        Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
            Route::get('/', function () {
                return view('users.index');
            })->name('index')->middleware('can:create user');
            Route::get('create', function () {
                return view('users.create');
            })->name('create')->middleware('can:create user');
            Route::get('{user}/edit', [UserController::class, 'edit'])->name('edit')->middleware('can:edit user');
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
        Route::group(['prefix' => 'artikel', 'as' => 'article.'], function () {
            Route::get('/', function () {
                return view('articles.index');
            })->name('index');
            Route::get('create', function () {
                return view('articles.create');
            })->name('create');
            Route::get('{article}/edit', [ArticleController::class, 'edit'])->name('edit');
            Route::get('{article:slug}', [ArticleController::class, 'show'])->name('show');
        });

        // route permission
        Route::group(['prefix' => 'permission', 'as' => 'permission.'], function () {
            Route::get('/', function () {
                return view('permissions.index');
            })->name('index');
            Route::get('create', function () {
                return view('permissions.create');
            })->name('create');
            Route::get('{permission}/edit', [PermissionController::class, 'edit'])->name('edit');
        });

        // route role
        Route::group(['prefix' => 'role', 'as' => 'role.'], function () {
            Route::get('/', function () {
                return view('roles.index');
            })->name('index');
            Route::get('create', function () {
                return view('roles.create');
            })->name('create');
            Route::get('{role}/edit', [RoleController::class, 'edit'])->name('edit');
        });

    });
});

require __DIR__ . '/auth.php';
