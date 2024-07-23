<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskListController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('auth.login');
    Route::post('/login', 'login');
    Route::get('/register', 'showRegisterForm')->name('auth.register');
    Route::post('/register', 'register');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

    Route::view('/', 'tasks')->name('tasks');
    Route::resource('tasks', TaskController::class);
    Route::resource('task-lists', TaskListController::class);
});
