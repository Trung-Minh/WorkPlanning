<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlansController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'doRegister']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'doLogin']);

Route::get('/reminders', function () {
    return view('reminders');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/ke-hoach', [PlansController::class, 'index'])->name('kehoach.index');
    Route::post('/ke-hoach', [PlansController::class, 'store']);
    Route::post('/cong-viec', [PlansController::class, 'storeTask']);
    Route::delete('/cong-viec/{id}', [PlansController::class, 'destroyTask']);
});
