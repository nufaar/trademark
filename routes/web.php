<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TrademarkController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

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

Volt::route('/', 'front.index')->name('front.index');
Volt::route('pengumuman', 'front.announcement.index')->name('front.announcement');
Volt::route('pengumuman/{article:slug}', 'front.announcement.detail')->name('front.announcement.show');
Volt::route('permohonan', 'front.trademark')->name('front.trademark');

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
        Route::group(['prefix' => 'user', 'as' => 'user.', 'middleware' => ['can:delete user']], function () {
            Route::get('/', function () {
                return view('users.index');
            })->name('index')->middleware('can:create user');
            Route::get('create', function () {
                return view('users.create');
            })->name('create')->middleware('can:create user');
            Route::get('{user}/edit', [UserController::class, 'edit'])->name('edit');
        });

        // route trademark
        Route::group(['prefix' => 'trademark', 'as' => 'trademark.'], function () {
            Volt::route('/', 'trademarks.index')->name('index');
            Volt::route('create', 'trademarks.create')->name('create');
            Volt::route('{trademark}/edit', 'trademarks.edit')->name('edit');
            Volt::route('{trademark}/show', 'trademarks.show')->name('show');
        });

        // route artikel
        Route::group(['prefix' => 'artikel', 'as' => 'article.', 'middleware' => ['can:delete article']], function () {
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
        Route::group(['prefix' => 'permission', 'as' => 'permission.', 'middleware' => ['can:delete user']], function () {
            Route::get('/', function () {
                return view('permissions.index');
            })->name('index');
            Route::get('create', function () {
                return view('permissions.create');
            })->name('create');
            Route::get('{permission}/edit', [PermissionController::class, 'edit'])->name('edit');
        });

        // route role
        Route::group(['prefix' => 'role', 'as' => 'role.', 'middleware' => ['can:delete user']], function () {
            Route::get('/', function () {
                return view('roles.index');
            })->name('index');
            Route::get('create', function () {
                return view('roles.create');
            })->name('create');
            Route::get('{role}/edit', [RoleController::class, 'edit'])->name('edit');
        });

        // route report
        Route::group(['prefix' => 'report', 'as' => 'report.', 'middleware' => ['can:delete user']], function () {
            Volt::route('/', 'report.index')->name('index');
        });

    });
});

require __DIR__ . '/auth.php';
