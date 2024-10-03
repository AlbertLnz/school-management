<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('web.dashboard');
Route::get('/dashboard/students/{id}', [DashboardController::class, 'index2'])->name('web.dashboard2');
