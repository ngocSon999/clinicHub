<?php

use App\Http\Controllers\BranchController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/greeting/{locale}', [DashboardController::class, 'setLocale'])->name('locale');

Route::middleware(['auth', 'verified', 'set_team'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/get-communes', [BranchController::class, 'getCommunes'])->name('branches.get-communes');
    Route::get('/switch', [BranchController::class, 'switchBranch'])->name('branches.switch');

    Route::prefix('role')->group(function () {
        Route::middleware('role_or_permission:super_admin|role.list')->group(function () {
            Route::get('', [RoleController::class, 'index'])->name('role.index');
            Route::get('/list', [RoleController::class, 'getList'])->name('role.list');
        });

        Route::middleware('role_or_permission:super_admin|role.create')->group(function () {
            Route::get('/create', [RoleController::class, 'create'])->name('role.create');
            Route::post('/store', [RoleController::class, 'store'])->name('role.store');
        });

        Route::middleware('role_or_permission:super_admin|role.edit')->group(function () {
            Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('role.edit');
            Route::put('/{role}/update', [RoleController::class, 'update'])->name('role.update');
        });

        Route::middleware('role_or_permission:super_admin|role.delete')->group(function () {
            Route::delete('/{role}/delete', [RoleController::class, 'destroy'])->name('role.destroy');
        });
    });

    Route::prefix('user')->group(function () {
        Route::middleware('role_or_permission:super_admin|user.list')->group(function () {
            Route::get('', [UserController::class, 'index'])->name('user.index');
            Route::get('/list', [UserController::class, 'getList'])->name('user.list');
        });

        Route::middleware('role_or_permission:super_admin|user.create')->group(function () {
            Route::get('/create', [UserController::class, 'create'])->name('user.create');
            Route::post('/store', [UserController::class, 'store'])->name('user.store');
        });

        Route::middleware('role_or_permission:super_admin|user.edit')->group(function () {
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
            Route::put('/{user}/update', [UserController::class, 'update'])->name('user.update');
        });

        Route::middleware('role_or_permission:super_admin|user.delete')->group(function () {
            Route::delete('/{user}/delete', [UserController::class, 'destroy'])->name('user.destroy');
        });

        Route::middleware('role_or_permission:super_admin|user.show')->group(function () {
            Route::get('/{user}/show', [UserController::class, 'show'])->name('user.show');
        });
    });

    Route::prefix('branches')->group(function () {
        Route::middleware('role_or_permission:super_admin|branch.list')->group(function () {
            Route::get('', [BranchController::class, 'index'])->name('branch.index');
            Route::get('/list', [BranchController::class, 'getList'])->name('branch.list');
        });

        Route::middleware('role_or_permission:super_admin|branch.create')->group(function () {
            Route::get('/create', [BranchController::class, 'create'])->name('branch.create');
            Route::post('/store', [BranchController::class, 'store'])->name('branch.store');
        });

        Route::middleware('role_or_permission:super_admin|branch.edit')->group(function () {
            Route::get('/{branch}/edit', [BranchController::class, 'edit'])->name('branch.edit');
            Route::put('/{branch}/update', [BranchController::class, 'update'])->name('branch.update');
        });

        Route::middleware('role_or_permission:super_admin|branch.delete')->group(function () {
            Route::delete('/{branch}/delete', [BranchController::class, 'destroy'])->name('branch.destroy');
        });
    });
});

require __DIR__.'/auth.php';
