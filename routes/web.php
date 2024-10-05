<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::redirect('/', '/dashboard', 307); # Temporary redirect (login page)

Route::get('/dashboard', [DashboardController::class, 'index'])->name('web.dashboard');
Route::get('/dashboard/students/{id}', [DashboardController::class, 'index2'])->name('web.dashboard2');
Route::get('/dashboard/students/{studentId}/{subjectId}/grades', [DashboardController::class, 'index3'])->name('web.dashboard3');
