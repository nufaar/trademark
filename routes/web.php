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


Route::middleware(['auth'])->group(function () {
    Route::view('user/profile', 'users.profile')
        ->name('user.profile');
    Route::middleware(['verified'])->group(function () {

        // route user
        Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
            Route::view('/', 'users.index')->name('index')->middleware('can:show user');
            Route::view('create', 'users.create')->name('create')->middleware('can:create user');
            Route::get('{user}/edit', [UserController::class, 'edit'])->name('edit')->middleware('can:edit user');
        });

        // route trademark
        Route::group(['prefix' => 'trademark', 'as' => 'trademark.'], function () {
            Volt::route('/', 'trademarks.index')->name('index')->middleware('can:show trademark');
            Volt::route('create', 'trademarks.create')->name('create')->middleware('can:create trademark');
            Volt::route('{trademark}/edit', 'trademarks.edit')->name('edit')->middleware('can:edit trademark');
            Volt::route('{trademark}/show', 'trademarks.show')->name('show')->middleware('can:show trademark');
        });

        // route artikel
        Route::group(['prefix' => 'artikel', 'as' => 'article.', 'middleware' => ['can:delete articles']], function () {
            Route::view('/', 'articles.index' )->name('index')->middleware('can:show articles');
            Route::view('create', 'articles.create')->name('create')->middleware('can:create articles');
            Route::get('{article}/edit', [ArticleController::class, 'edit'])->name('edit')->middleware('can:edit articles');
            Route::get('{article:slug}', [ArticleController::class, 'show'])->name('show')->middleware('can:show articles');
        });

        // route permission
        Route::group(['prefix' => 'permission', 'as' => 'permission.', 'middleware' => ['can:delete user']], function () {
            Route::view('/', 'permissions.index')->name('index')->middleware('can:show permission');
            Route::view('create', 'permissions.create')->name('create')->middleware('can:create permission');
            Route::get('{permission}/edit', [PermissionController::class, 'edit'])->name('edit')->middleware('can:edit permission');
        });

        // route role
        Route::group(['prefix' => 'role', 'as' => 'role.', 'middleware' => ['can:delete user']], function () {
            Route::view('/', 'roles.index')->name('index')->middleware('can:show role');
            Route::view('create', 'roles.create')->name('create')->middleware('can:create role');
            Route::get('{role}/edit', [RoleController::class, 'edit'])->name('edit')->middleware('can:edit role');
        });

        // route report
        Route::group(['prefix' => 'report', 'as' => 'report.', 'middleware' => ['can:show report']], function () {
            Volt::route('/', 'report.index')->name('index');
        });

    });
});

require __DIR__ . '/auth.php';
