<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\EntrantController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\OrganisationController;
use App\Http\Controllers\TicketController;
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

Route::resources([
    'entrants'   => EntrantController::class,
    'events'        => EventController::class,
    'organisations' => OrganisationController::class,
]);

Route::controller(TicketController::class)
    ->middleware(['auth'])
    ->prefix('/events/{event}/tickets')
    ->name('events.tickets.')
    ->group(function ()
    {
        Route::post('/checkout', 'checkout')->name('checkout');
});
Route::resource('events.tickets', TicketController::class)->except(['index']);
