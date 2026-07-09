<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Book;
use App\Models\Borrowing;

class LibraryController extends Controller
{
    public function arahkanHalaman() 
    {
        $user = Auth::user();

        if ($user->role == 'penjaga') {
            $semuaPinjaman = Borrowing::all();
            return view('halaman_penjaga', compact('semuaPinjaman'));
        } else {
            $semuaBuku = Book::all();
            return view('halaman_buku', compact('semuaBuku'));
        }
    }


    public function prosesPinjam(Request $request) 
    {
        //ambil judul buku dari input form hidden (name="judul_buku")
        $judulBukuDariForm = $request->judul_buku;

        //cari data buku yang sesuai di database
        $buku = Book::where('judul',$judulBukuDariForm)->first();
        
        //pengaman: jika buku tidak ditemukan, gagalkan proses
        if (!$buku) {
            
            return redirect('/dashboard')->with('error','Aduh, buku tidak ditemukan!');
        }

        //ubah status buku tersebut menjadi "dipinjam'
        $buku->status = "Dipinjam";
        $buku->save();

        //catat riwayat transaksi ke dalam tabel borrowings
        Borrowing::create([
            'nama_peminjam' => Auth::user()->name,
            'judul_buku'    => $buku->judul
        ]);

        return redirect('/dashboard')->with('sukses', 'Berhasil meminjam buku' . $buku->judul . '!');
    }

    //buat satu fungsi yang digunakan untuk menerima buku yang dipinjam
   public function prosesKembali(Request $request)
{
    // 🌟 KUNCI UTAMA: Gunakan trim() untuk menghapus spasi gaib di awal/akhir judul buku
    $judulBuku = trim($request->judul_buku);

    // 1. Cari data buku dengan pencarian yang lebih fleksibel (menggunakan LIKE)
    $buku = Book::where('judul', 'LIKE', $judulBuku)->first();

    if ($buku) {
        // 2. Ubah status buku menjadi 'Tersedia' kembali
        $buku->status = 'Tersedia';
        $buku->save();

        // 3. Hapus catatan peminjaman dari tabel borrowings secara bersih
        Borrowing::where('judul_buku', 'LIKE', $judulBuku)->delete();

        // 4. Alihkan kembali ke dashboard dengan membawa pesan sukses
        return redirect('/dashboard')->with('sukses', 'Buku "' . $buku->judul . '" telah berhasil dikembalikan!');
    }

    // Jika pencarian gagal, lempar notifikasi eror agar kita tahu masalahnya
    return redirect('/dashboard')->with('error', 'Aduh! Gagal mengubah status buku "' . $judulBuku . '" di database.');

}
    public function uploadFoto(Request $request)
    {
        $request->validate([
            'foto_galeri' => 'required|image|mimes:jpeg.png.jpg.gif|max:2048',
        ]);

        if ($request->hasFile('foto_galeri')) {
            $user =\App\Models\User::find(auth()->user()->id);

            if ($user->foto && \Storage::disk('public')->exists('foto_profil/' . $user->foto)) {
            \Storage::disk('public')->delete('foto_profil/' . $user->foto);
          }

          $file = $request->file('foto_galeri');
          $namaFile = 'foto_user' . $user->id . '_' .time() . '_' . $file->getClientOriginalExtension();

          $file->storeAs('foto_profil',$namaFile,'public');

          $user->update(['foto'=> $namaFile]);
          
          return redirect('/dashboard')->with('sukses','Foto profil berhasil diperbarui!');
        }

        return redirect('/dashboard')->with('error','Gagal mengunggah foto profil.');
    }
}
