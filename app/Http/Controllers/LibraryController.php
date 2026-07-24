<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Book;
use App\Models\Borrowing;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class libraryController extends Controller
{
    // Fungsi untuk menampilkan halaman utama
    public function index(Request $request)
    {
        $query = Book::query();
        if ($request->has('kategori') && $request->kategori) {
            $query->where('kategori', $request->kategori);
        }
        $semuaBuku = $query->get();
        $bukuTerlambat = Borrowing::where('tanggal_kembali', '<', Carbon::now())->get();

        $peminjamansaya = collect();

        if (Auth::check() && strtolower(Auth::user()->role) === 'peminjam') {
            $peminjamansaya = Borrowing::where('nama_peminjam', Auth::user()->name)
                ->get()
                ->map(function ($item) {
                    // hitung denda jika terlambat (misal Rp 1.000 / hari)
                    $tglKembali = Carbon::parse($item->tanggal_kembali);
                    $sekarang = Carbon::now();

                    if ($sekarang->greaterThan(tglKembali)) {
                        $hariTerlambat = $sekarang->diffInDays(tglKembali);
                        $item->denda = $hariTerlambat * 1000; // ubah nominal sesui ketentuan
                    } else {
                        $item->denda = 0;
                    }

                    return $item;
                });
        }

        return view('dashboard', compact('semuaBuku', 'bukuTerlambat', 'peminjamansaya'));
    }

    // ==========================================
    // KHUSUS PENJAGA PERPUSTAKAAN (CRUD BUKU)
    // ==========================================

    // 1. Simpan Buku Baru + Upload Sampul Pertama
    public function storeBook(Request $request)
    {
        // Proteksi Hak Akses
        if (!Auth::check() || strtolower(Auth::user()->role) !== 'penjaga') {
            abort(403, 'Akses Ditolak! Hanya Penjaga yang boleh menambah buku.');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'kategori' => 'required|string',
            'deskripsi' => 'required',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $buku = new Book();
        $buku->judul = $request->judul;
        $buku->penulis = $request->penulis;
        $buku->kategori = $request->kategori;
        $buku->deskripsi = $request->deskripsi;
        $buku->status = 'tersedia';

        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('covers', 'public');
            $buku->cover = $path;
        }

        $buku->save();

        return redirect()->back()->with('sukses', 'Buku baru berhasil disimpan!');
    }

    // 2. Form Edit Buku
    public function editBook($id)
    {
        // Proteksi Hak Akses
        if (!Auth::check() || strtolower(Auth::user()->role) !== 'penjaga') {
            abort(403, 'Akses Ditolak! Hanya Penjaga yang boleh mengedit buku.');
        }

        $buku = Book::findOrFail($id);
        return view('books.edit', compact('buku'));
    }

    // 3. Update Data Buku & Ganti Sampul
    public function update(Request $request, $id)
    {
        // Proteksi Hak Akses
        if (!Auth::check() || strtolower(Auth::user()->role) !== 'penjaga') {
            abort(403, 'Akses Ditolak! Hanya Penjaga yang boleh mengubah buku.');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'kategori' => 'required|string',
            'deskripsi' => 'nullable|string',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $buku = Book::findOrFail($id); 
        $buku->judul = $request->judul;
        $buku->penulis = $request->penulis;
        $buku->kategori = $request->kategori;
        if ($request->has('deskripsi')) {
            $buku->deskripsi = $request->deskripsi;
        }

        // Jika user mengunggah sampul baru
        if ($request->hasFile('cover')) {
            // Hapus file sampul lama jika ada
            if ($buku->cover && Storage::disk('public')->exists($buku->cover)) {
                Storage::disk('public')->delete($buku->cover);
            }

            // Simpan file sampul baru
            $path = $request->file('cover')->store('covers', 'public');
            $buku->cover = $path;
        }

        $buku->save();

        return redirect()->back()->with('sukses', 'Buku dan Sampul berhasil diperbarui!');
    }

    // 4. Hapus Buku & Sampulnya
    public function destroy($id)
    {
        // Proteksi Hak Akses
        if (!Auth::check() || strtolower(Auth::user()->role) !== 'penjaga') {
            abort(403, 'Akses Ditolak! Hanya Penjaga yang boleh menghapus buku.');
        }

        $buku = Book::findOrFail($id);

        // Hapus gambar sampul dari storage jika ada
        if ($buku->cover && Storage::disk('public')->exists($buku->cover)) {
            Storage::disk('public')->delete($buku->cover);
        }

        $buku->delete();

        return redirect()->back()->with('sukses', 'Buku berhasil dihapus!');
    }

    public function detailBook($id)
    {
        $buku = Book::findOrFail($id);

        return view('books.show', compact('buku'));
    }

    // ==========================================
    // FUNGSI PEMINJAMAN & PENGEMBALIAN
    // ==========================================

    // Fungsi Pinjam
    public function prosesPinjam(Request $request)
    {
        $request->validate(['judul_buku' => 'required']);
        $buku = Book::where('judul', trim($request->judul_buku))->first();

        if ($buku && trim($buku->status) === 'tersedia') {
            $buku->update(['status' => 'dipinjam']);

            Borrowing::create([
                'nama_peminjam'   => Auth::user()->name,
                'judul_buku'      => $buku->judul,
                'tanggal_kembali' => Carbon::now()->addDays(7)
            ]);

            return redirect()->back()->with('sukses', 'Berhasil meminjam: ' . $buku->judul);
        }

        return redirect()->back()->with('error', 'Maaf, buku tidak tersedia!');
    }

    // Fungsi Kembali
    public function prosesKembali(Request $request)
    {
        $request->validate(['judul_buku' => 'required']);
        $buku = Book::where('judul', trim($request->judul_buku))->first();

        if ($buku) {
            $buku->update(['status' => 'tersedia']);
            Borrowing::where('judul_buku', $buku->judul)->delete();
            
            return redirect()->back()->with('sukses', 'Buku ' . $buku->judul . ' berhasil dikembalikan.');
        }
        
        return redirect()->back()->with('error', 'Gagal memproses pengembalian.');
    }

    public function daftarPeminjaman()
    {
        $peminjaman = Borrowing::all();
        return view('peminjaman.index', compact('peminjaman'));
    }

    public function kembalikan(Request $request) 
    {
        $peminjaman = Borrowing::findOrFail($request->id);
        $peminjaman->delete();
        
        return redirect()->back()->with('sukses', 'Buku berhasil dikembalikan.');
    }
}