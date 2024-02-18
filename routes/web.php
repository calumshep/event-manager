<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HelpArticleController;
use App\Http\Controllers\OrganisationController;
use App\Http\Controllers\TicketTypeController;
use App\Http\Controllers\OrderController;
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

Route::controller(HomeController::class)
    ->name('home')
    ->group(function ()
{
    Route::get('/', 'dashboard');
    Route::get('event/{event:slug}', 'event')->name('.event');
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
    'events'        => EventController::class,
    'organisations' => OrganisationController::class,
]);

Route::controller(OrderController::class)
    ->prefix('/event/{event:slug}/')
    ->name('events.tickets.')
    ->group(function ()
    {
        Route::post('/checkout', 'checkout')->name('checkout');
        Route::post('/purchase', 'purchase')->name('purchase');
        Route::get('/purchase', 'cancelled')->name('cancelled');
        Route::get('/purchase/success', 'success')->name('purchase.success');
});

Route::resource('events.tickets', TicketTypeController::class)
    ->parameters(['tickets' => 'ticket_type'])
    ->except(['index']);

Route::resource('help', HelpArticleController::class)
    ->parameters('helpArticle')
    ->except('show');

Route::controller(HelpArticleController::class)
    ->prefix('/help')
    ->name('help.')
    ->group(function ()
    {
        Route::get('/{helpArticle:slug}')->name('show');
});
