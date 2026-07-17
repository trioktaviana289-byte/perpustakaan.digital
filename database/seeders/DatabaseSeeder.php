<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // <-- Wajib import ini untuk enkripsi password

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Membuat akun Penjaga (Akan otomatis diarahkan ke halaman penjaga)
        User::create([
            'name' => 'Penjaga Perpustakaan',
            'email' => 'penjaga@gmail.com',
            'password' => Hash::make('password123'), // Terenkripsi aman dengan Bcrypt
            'role' => 'penjaga',
        ]);

        // 2. Membuat akun Peminjam / Pelanggan
        User::create([
            'name' => 'Budi Peminjam',
            'email' => 'peminjam@gmail.com',
            'password' => Hash::make('password123'), // Terenkripsi aman dengan Bcrypt
            'role' => 'peminjam',
        ]);
    }
}