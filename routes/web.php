<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\libraryController;
use Illuminate\Support\Facades\Route;

// 1. Rute Halaman Utama / Login
Route::get('/', function () { 
    return view('auth.login'); 
});

// 2. Rute Dashboard (Bisa diakses setelah login)
Route::get('/dashboard', [libraryController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// 3. Kelompok Rute yang Wajib Login (Middleware Auth)
Route::middleware('auth')->group(function () {
    
    // --- MANAJEMEN PROFIL ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/avatar', [libraryController::class, 'updateAvatar'])->name('profile.avatar.update');

    // --- MANAJEMEN BUKU (Hanya Penjaga yang bisa aksi dari controller) ---
    // Simpan Buku Baru
    Route::post('/buku', [libraryController::class, 'storeBook'])->name('books.store');
    
    // Tampilkan Halaman Form Edit Buku (Baru Ditambahkan!)
    Route::get('/buku/{id}/edit', [libraryController::class, 'editBook'])->name('books.edit');
    
    // Update Data Buku & Ganti Sampul
    Route::put('/buku/{id}', [libraryController::class, 'update'])->name('books.update');
    
    // Hapus Buku (Baru Ditambahkan!)
    Route::delete('/buku/{id}', [libraryController::class, 'destroy'])->name('books.destroy');

    // --- FITUR PEMINJAMAN & PENGEMBALIAN ---
    Route::post('/pinjam-buku', [libraryController::class, 'prosesPinjam'])->name('books.pinjam');
    Route::post('/kembalikan-buku', [libraryController::class, 'prosesKembali'])->name('books.kembalikan');
    Route::get('/daftar-peminjaman', [libraryController::class, 'daftarPeminjaman'])->name('peminjaman.index');
});

require __DIR__.'/auth.php';