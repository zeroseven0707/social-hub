<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FacebookController;
use App\Http\Controllers\ExportController;
Route::get('/', function () {
    return view('welcome');
});
Route::get('/facebook/accounts', [FacebookController::class, 'getAccounts']);
Route::get('/pages/{id}/{token}', [FacebookController::class, 'getPages']);
Route::get('/dashboard/{id}', [FacebookController::class, 'getInsights'])->name('getInsights');
Route::post('/update-metrics', [FacebookController::class, 'updateMetrics'])->name('updateMetrics');



Route::get('/export-csv', [ExportController::class, 'exportToCsv']);
