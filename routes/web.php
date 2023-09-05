<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/




Route::middleware('auth')->group(function () {
    // Dashboard Route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Teams Routes
    Route::get('/teams', [TeamController::class, 'index'])->name('teams');
    Route::post('/add-team', [TeamController::class, 'add'])->name('add-team');
    Route::delete('/delete-team', [TeamController::class, 'delete'])->name('delete-team');
    Route::any('/teams/search', [TeamController::class, 'search'])->name('teams-search');
    Route::get('/team', [TeamController::class, 'teamIndex'])->name('team');
    Route::any('/team/search', [TeamController::class, 'searchMembers'])->name('member-search');
    Route::put('/toggle-team-admin', [TeamController::class, 'toggleTeamAdmin'])->name('toggle-team-admin');
    Route::put('/remove-member', [TeamController::class, 'removeTeamMember'])->name('remove-member');
    Route::put('/add-member', [TeamController::class, 'addTeamMember'])->name('add-member');

    // Users Routes
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::put('/edit-user', [UserController::class, 'edit'])->name('edit-user');

    // Tasks Routs
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks');
});
