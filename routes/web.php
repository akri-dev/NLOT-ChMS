<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;


Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

Route::middleware(['auth', 'can:system-admin'])->group(function () {
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    Route::get('/admin/profiles', [ProfileController::class, 'index'])->name('admin.profiles.index');
    Route::put('/admin/profiles/{profile}', [ProfileController::class, 'update'])->name('admin.profiles.update');
    Route::delete('/admin/profiles/{profile}', [ProfileController::class, 'destroy'])->name('admin.profiles.destroy');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
