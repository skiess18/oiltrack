<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\RoutePlanController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Обекти
    |--------------------------------------------------------------------------
    */

    Route::resource('clients', ClientController::class);

    /*
    |--------------------------------------------------------------------------
    | Събирания
    |--------------------------------------------------------------------------
    */

    Route::get('/clients/{client}/collections', [CollectionController::class, 'index'])
        ->name('collections.index');

    Route::get('/clients/{client}/collections/create', [CollectionController::class, 'create'])
        ->name('collections.create');

    Route::post('/clients/{client}/collections', [CollectionController::class, 'store'])
        ->name('collections.store');

    /*
    |--------------------------------------------------------------------------
    | Маршрути
    |--------------------------------------------------------------------------
    */

    Route::resource('routes', RoutePlanController::class);

    // Стартиране на маршрут
    Route::get('/routes/{route}/drive', [RoutePlanController::class, 'drive'])
        ->name('routes.drive');

    // Маркирай като посетен
    Route::post('/routes/{route}/clients/{client}/visit', [RoutePlanController::class, 'visit'])
        ->name('routes.visit');

    // GPS пристигане (нова функция)
    Route::post('/routes/{route}/clients/{client}/arrive', [RoutePlanController::class, 'arrive'])
        ->name('routes.arrive');

    /*
    |--------------------------------------------------------------------------
    | Карта
    |--------------------------------------------------------------------------
    */

    Route::get('/map', [MapController::class, 'index'])
        ->name('map.index');

});

require __DIR__.'/auth.php';