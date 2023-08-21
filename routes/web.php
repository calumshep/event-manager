<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\CompetitorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\OrganisationController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () { return view('welcome'); })->name('home');
Route::controller(DashboardController::class)
    ->middleware(['auth'])
    ->prefix('/dashboard')
    ->name('dashboard')
    ->group(function ()
{
    Route::get('/', 'dashboard');
    Route::get('/{event}', 'event')->name('.event');
});

require __DIR__.'/auth.php';

// Account management
Route::controller(AccountController::class)
    ->middleware(['auth'])
    ->prefix('/account')
    ->name('account.')
    ->group(function ()
    {
        Route::get('/', 'showOwn')->name('show-own');
        Route::get('/{user}', 'show')->name('show'); // TODO: apply admin check middleware
        Route::get('/{user}/edit', 'edit')->name('edit');
        Route::put('/{user}', 'update')->name('update');
});

Route::resource('competitors', CompetitorController::class)->middleware(['auth']);
Route::resource('events', EventController::class)->middleware(['auth']);
Route::resource('organisations', OrganisationController::class)->middleware(['auth']);
