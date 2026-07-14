<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Book;
use App\Models\Borrowing;
use Carbon\Carbon;

class libraryController extends Controller
{
 public function arahkanHalaman(Request $request)
{
    $user = Auth::user();

    // 1. Ambil data keterlambatan pengembalian buku
    $bukuTerlambat = Borrowing::where('tanggal_kembali', '<', Carbon::now())->get();

    // 2. Ambil parameter kategori dari URL (?kategori=...)
    $kategoriDipilih = $request->query('kategori');

    // 3. Ambil data buku berdasarkan kategori yang diklik
    if ($kategoriDipilih) {
        $semuaBuku = Book::where('kategori', $kategoriDipilih)->get();
    } else {
        $semuaBuku = Book::all();
    }

    // 4. BAGIAN YANG DIUBAH: Pisahkan halaman berdasarkan role penjaga vs peminjam
    if ($user && (strtolower($user->role) === 'penjaga' || strtolower($user->role) === 'admin')) {
        $semuaPinjaman = Borrowing::all();
        
        // Mengarahkan penjaga ke file view 'resources/views/penjaga.blade.php'
        return view('halaman_penjaga', compact('semuaPinjaman', 'bukuTerlambat', 'semuaBuku'));
    } else {
        // Mengarahkan peminjam biasa ke file view 'resources/views/dashboard.blade.php'
        return view('dashboard', compact('bukuTerlambat', 'semuaBuku'));
    }
}

    // 2. Memproses aksi peminjaman buku dari siswa/peminjam
    public function prosesPinjam(Request $request)
    {
        $judulBukuDariForm = $request->judul_buku;
        $buku = Book::where('judul', $judulBukuDariForm)->first();

        if (!$buku) {
            return redirect('/dashboard')->with('error', 'Aduh, buku tidak ditemukan!');
        }

        $buku->status = 'Dipinjam';
        $buku->save();

        // Catat riwayat transaksi ke dalam tabel borrowings
        // Ditambahkan tanggal_kembali otomatis (7 hari dari sekarang)
        Borrowing::create([
            'nama_peminjam'   => Auth::user()->name,
            'judul_buku'      => $buku->judul,
            'tanggal_kembali' => Carbon::now()->addDays(7) 
        ]);

        return redirect('/dashboard')->with('sukses', 'Berhasil meminjam buku ' . $buku->judul . '!');
    }

    // 3. Memproses pengembalian buku
    public function prosesKembali(Request $request)
    {
        $judulBuku = trim($request->judul_buku);
        $buku = Book::where('judul', 'LIKE', $judulBuku)->first();

        if ($buku) {
            $buku->status = 'Tersedia';
            $buku->save();

            Borrowing::where('judul_buku', 'LIKE', $judulBuku)->delete();

            return redirect('/dashboard')->with('sukses', 'Buku "' . $buku->judul . '" telah berhasil dikembalikan!');
        }

        return redirect('/dashboard')->with('error', 'Aduh! Gagal mengubah status buku "' . $judulBuku . '" di database.');
    }
}