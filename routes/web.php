<?php

// 1. KELOMPOK IMPORT (Harus selalu di paling atas file di bawah <?php)
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\libraryController;
use Illuminate\Support\Facades\Route;

// 2. KELOMPOK RUTE UMUM & LOGIN
Route::get('/', function () {
    return view('auth.login');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// 3. KELOMPOK RUTE APLIKASI PERPUSTAKAAN
Route::get('/dashboard', [libraryController::class, 'arahkanHalaman'])
     ->middleware(['auth', 'verified'])
     ->name('dashboard');

Route::post('/pinjam-buku', [libraryController::class, 'prosesPinjam'])
    ->middleware('auth');

Route::post('/kembalikan-buku', [libraryController::class, 'prosesKembali'])
    ->middleware('auth');

// 4. KELOMPOK RUTE PROFILE BAWAAN LARAVEL (Untuk mengatasi RouteNotFoundException)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
    
require __DIR__.'/auth.php';