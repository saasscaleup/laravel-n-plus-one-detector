<?php

use Illuminate\Support\Facades\Route;
use Saasscaleup\NPlusOneDetector\NPlusOneDashboardController;

Route::get('/n-plus-one-dashboard', [NPlusOneDashboardController::class, 'index']);
Route::get('/api/n-plus-one-logs', [NPlusOneDashboardController::class, 'logs']);