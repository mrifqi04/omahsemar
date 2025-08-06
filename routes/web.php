<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('auth.login');
})->middleware('guest');

Auth::routes(['register' => false]);

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('/sales-purchases/chart-data', [HomeController::class, 'salesPurchasesChart'])
        ->name('sales-purchases.chart');

    Route::get('/current-month/chart-data', [HomeController::class, 'currentMonthChart'])
        ->name('current-month.chart');

    Route::get('/payment-flow/chart-data', [HomeController::class, 'paymentChart'])
        ->name('payment-flow.chart');
});
