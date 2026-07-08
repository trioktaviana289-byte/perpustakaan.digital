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
    //
}
