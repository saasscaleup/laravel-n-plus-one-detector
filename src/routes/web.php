<?php

use Illuminate\Support\Facades\Route;
use Saasscaleup\NPlusOneDetector\NPlusOneDashboardController;

Route::get('/n-plus-one-dashboard', [NPlusOneDashboardController::class, 'index'])->name('n-plus-one.dashboard');
Route::delete('/n-plus-one-dashboard/{id}', [NPlusOneDashboardController::class, 'destroy'])->name('n-plus-one.dashboard.destroy');