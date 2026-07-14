<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\RoutePlanController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\TransportReportController;

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
    | Потребители
    |--------------------------------------------------------------------------
    */

    Route::middleware('admin')->group(function () {

        Route::resource('users', UserController::class);

    });

    /*
    |--------------------------------------------------------------------------
    | Обекти
    |--------------------------------------------------------------------------
    */

    Route::resource('clients', ClientController::class);

    /*
    |--------------------------------------------------------------------------
    | Транспортен отчет
    |--------------------------------------------------------------------------
    */

    Route::get('/transport-report', [TransportReportController::class, 'create'])
        ->name('transport-report.create');

    Route::post('/transport-report', [TransportReportController::class, 'store'])
        ->name('transport-report.store');

    Route::get('/transport-report/end', [TransportReportController::class, 'edit'])
        ->name('transport-report.edit');

    Route::put('/transport-report/end', [TransportReportController::class, 'update'])
        ->name('transport-report.update');

    /*
    |--------------------------------------------------------------------------
    | Автомобили
    |--------------------------------------------------------------------------
    */

    Route::resource('vehicles', VehicleController::class);

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

    Route::get('/collections/{collection}', [CollectionController::class, 'show'])
        ->name('collections.show');

    Route::get('/collections/{collection}/edit', [CollectionController::class, 'edit'])
        ->name('collections.edit');

    Route::put('/collections/{collection}', [CollectionController::class, 'update'])
        ->name('collections.update');

    Route::delete('/collections/{collection}', [CollectionController::class, 'destroy'])
        ->name('collections.destroy');

    Route::get('/collections/{collection}/pdf', [CollectionController::class, 'pdf'])
        ->name('collections.pdf');

    /*
    |--------------------------------------------------------------------------
    | Маршрути
    |--------------------------------------------------------------------------
    */

    Route::resource('routes', RoutePlanController::class);

    Route::get('/routes/{route}/drive', [RoutePlanController::class, 'drive'])
        ->name('routes.drive');

    Route::post('/routes/{route}/clients/{client}/visit', [RoutePlanController::class, 'visit'])
        ->name('routes.visit');

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