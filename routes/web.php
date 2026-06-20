<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/greeting/{locale}', [DashboardController::class, 'setLocale'])->name('locale');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('role')->group(function () {
        Route::get('/list', [RoleController::class, 'getList'])->name('role.list');
        Route::get('/create', [RoleController::class, 'create'])->name('role.create');
        Route::get('/', [RoleController::class, 'index'])->name('role.index');
    });
});

require __DIR__.'/auth.php';
