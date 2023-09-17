<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;

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

    // Projects Routes
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects');
    Route::post('/add-project', [ProjectController::class, 'add'])->name('add-project');
    Route::delete('/delete-project', [ProjectController::class, 'delete'])->name('delete-project');

    // Teams Routes
    Route::get('/teams', [TeamController::class, 'index'])->name('teams');
    Route::post('/add-team', [TeamController::class, 'add'])->name('add-team');
    Route::delete('/delete-team', [TeamController::class, 'delete'])->name('delete-team');
    Route::get('/team', [TeamController::class, 'teamIndex'])->name('team');
    Route::put('/toggle-team-admin', [TeamController::class, 'toggleTeamAdmin'])->name('toggle-team-admin');
    Route::put('/remove-member', [TeamController::class, 'removeTeamMember'])->name('remove-member');
    Route::put('/add-member', [TeamController::class, 'addTeamMember'])->name('add-member');

    // Users Routes
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::put('/edit-user', [UserController::class, 'edit'])->name('edit-user');

    // Tasks Routes
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks');
    Route::post('/add-task', [TaskController::class, 'add'])->name('add-task');
    Route::get('/task', [TaskController::class, 'taskIndex'])->name('task');
    Route::put('/edit-task', [TaskController::class, 'editTask'])->name('edit-task');
    Route::delete('/delete-task', [TaskController::class, 'deleteTask'])->name('delete-task');
    Route::post('/accept-task', [TaskController::class, 'acceptTask'])->name('accept-task');
    Route::post('/reject-task', [TaskController::class, 'rejectTask'])->name('reject-task');
    Route::post('/submit-task', [TaskController::class, 'submitTask'])->name('submit-task');
    Route::delete('/delete-task-member', [TaskController::class, 'deleteTaskMember'])->name('delete-task-member');
    Route::post('/add-task-members', [TaskController::class, 'addTaskMembers'])->name('add-task-members');

    // Comments Routes
    Route::post('/add-comment', [CommentController::class, 'addComment'])->name('add-comment');

    //Locale Routs
    Route::post('/change-locale', [LocaleController::class, 'changeLocale'])->name('change-locale');
});
