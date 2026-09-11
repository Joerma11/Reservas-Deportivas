<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas Administrativas
Route::middleware(['auth'])->prefix('admin')->group(function () {
    
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Módulo de Gestión de Canchas
    Route::get('/canchas', function () {
        return view('admin.fields');
    })->name('admin.fields');

    // Módulo de Reservas
    Route::get('/reservas', function () {
        return view('admin.bookings');
    })->name('admin.bookings');

    // Módulo de Reportes
    Route::get('/reportes', function () {
        return view('admin.reports');
    })->name('admin.reports');
});


require __DIR__.'/auth.php';
