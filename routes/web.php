<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StatisticsController;

Route::view('/', 'welcome');
Route::view('/view', 'statistics')->name('statistics');

Route::get('/view/fetch-statistic', [StatisticsController::class, 'viewStatistic'])->name('statistics.fetch-statistic');
Route::get('/view/download', [StatisticsController::class, 'download'])->name('statistics.download');
