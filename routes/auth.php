<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Auth routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group.
|
*/

Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate']);

    Route::get('/register', [RegisterController::class, 'index']);
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/reset-password', [ResetPasswordController::class, 'index']);
    Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword'])->name('password.email');

    Route::get('/change-password/{token}', [ResetPasswordController::class, 'changePasswordIndex'])->name('password.reset');
    Route::post('/change-password', [ResetPasswordController::class, 'changePassword'])->name('password.update');
});

Route::middleware('auth')->group(function () {

    Route::post('/logout', [LoginController::class, 'logout']);
});
