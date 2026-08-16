<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Home
Route::get('/', function () {
    return view('welcome');
});

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

// Authentication
require __DIR__ . '/auth.php';


// =====================================================
// MANAGEMENT
// Admin and Staff can access
// =====================================================

Route::middleware(['auth', 'role:admin,staff'])->group(function () {

    Route::get('/management', function () {
        return view('management');
    })->name('management');

});


// =====================================================
// ADMIN
// Only Admin can access
// =====================================================

Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/admin', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

});


// =====================================================
// STAFF
// Only Staff can access
// =====================================================

Route::middleware(['auth', 'role:staff'])->group(function () {

    Route::get('/staff', function () {
        return view('staff.dashboard');
    })->name('staff.dashboard');

});