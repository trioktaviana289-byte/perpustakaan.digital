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

        $deskripsiBuku = [
    "Sejarah Indonesia Modern 1200-2008" => "Sebuah karya referensi akademis yang otoritatif, melacak transformasi mendalam Indonesia dari masa kerajaan-kerajaan besar hingga dinamika politik era kontemporer, memberikan pemahaman komprehensif mengenai fondasi sosial-politik bangsa.",
    "Indonesia Menggugat" => "Pledoi legendaris yang disampaikan oleh Soekarno di hadapan pengadilan kolonial Belanda, yang bukan sekadar pembelaan diri, melainkan kritik tajam terhadap imperialisme dan seruan bagi kemerdekaan bangsa Indonesia.",
    "Sapiens: A Brief History of Humankind" => "Penelusuran ambisius mengenai evolusi manusia, mulai dari spesies purba yang tidak signifikan hingga menjadi penguasa planet berkat kemampuan unik kita untuk menciptakan mitos bersama seperti uang, negara, dan agama.",
    "Guns, Germs, and Steel" => "Eksplorasi mendalam mengenai bagaimana faktor geografis, ketersediaan sumber daya alam, dan lingkungan fisik menentukan keberhasilan atau kegagalan peradaban manusia dalam menguasai teknologi dan dominasi global.",
    "A Brief History of Time" => "Stephen Hawking membawa pembaca menjelajahi misteri terbesar alam semesta, mulai dari teori Big Bang, lubang hitam, hingga konsep waktu, disajikan dengan bahasa yang membuat topik kosmologi rumit menjadi dapat dipahami.",
    "The Selfish Gene" => "Buku revolusioner yang mengubah paradigma biologi dengan mengajukan argumen bahwa gen, bukan organisme, adalah unit utama seleksi alam, menjelaskan evolusi melalui sudut pandang genetik yang dingin namun fascinasi.",
    "Silent Spring" => "Karya monumental yang memicu lahirnya gerakan kesadaran lingkungan global, dengan mengungkap bahaya penggunaan pestisida kimia secara berlebihan yang mengancam keseimbangan ekosistem, burung, dan kesehatan manusia.",
    "The Gene" => "Kisah epik mengenai sejarah penemuan genetika yang menghubungkan sains, sejarah keluarga, dan masa depan manusia, mengeksplorasi bagaimana kode genetik menentukan identitas, keturunan, dan etika medis modern.",
    "Cosmos" => "Sebuah perjalanan naratif melalui ruang dan waktu yang merayakan keindahan sains, menghubungkan evolusi alam semesta selama miliaran tahun dengan sejarah perkembangan umat manusia dalam upaya memahami tempat kita di alam semesta.",
    "Laskar Pelangi" => "Kisah inspiratif tentang sepuluh anak dari keluarga kurang mampu di Belitung yang berjuang melawan keterbatasan fasilitas demi menuntut ilmu, sebuah ode untuk persahabatan, kegigihan, dan kekuatan impian yang melampaui kemiskinan.",
    "Bumi" => "Petualangan fantastis tentang tiga remaja yang menemukan rahasia besar bahwa dunia mereka bukanlah satu-satunya tempat, melainkan bagian dari dunia paralel yang menyimpan kekuatan luar biasa dan misteri kuno.",
    "Cantik itu Luka" => "Sebuah mahakarya realisme magis Indonesia yang berlatar masa kolonial hingga pasca-kemerdekaan. Mengisahkan kehidupan Dewi Ayu, seorang perempuan berdarah Belanda-Indonesia, dan anak-anak perempuannya yang dikutuk oleh kecantikan sekaligus tragedi. Novel ini memadukan sejarah kelam bangsa, mitos lokal, humor satire, dan drama keluarga yang berbobot namun sangat memikat.",
    "Gadis Kretek" => "Sebuah kisah romansa sejarah yang berlatar belakang perkembangan industri rokok kretek di Indonesia, mulai dari masa penjajahan Jepang hingga era modern. Lewat penelusuran tiga bersaudara mencari sosok perempuan misterius bernama Jeng Yah, novel ini mengungkap rahasia keluarga, persaingan bisnis kemulian rasa kretek, dan cinta tragis yang terkubur masa lalu.",
    "The Midnight Library" => "Sebuah novel fiksi-fantasi yang hangat dan penuh perenungan. Mengisahkan Nora Seed yang menemukan sebuah perpustakaan misterius di antara hidup dan mati, di mana setiap buku di dalamnya memberikan kesempatan untuk mencoba kehidupan lain yang bisa saja ia jalani jika mengambil keputusan berbeda. Kisah indah tentang berdamai dengan penyesalan dan mencari apa yang benar-benar membuat hidup ini berharga.",
    "Babad Tanah Jawi" => "Karya sastra sejarah legendaris yang menjadi rujukan utama silsilah raja-raja di tanah Jawa. Diterjemahkan oleh W. L. Olthof dari naskah aslinya, buku ini merangkum kronik, mitos, legenda, serta peristiwa sejarah penting mulai dari zaman kerajaan Hindu-Buddha, era wali sanga, hingga masa keemasan Kesultanan Mataram Islam.",

    ];

    return view('dashboard', compact('semuaBuku', 'bukuTerlambat', 'deskripsiBuku'));
    }

    // Fungsi Tambah Buku Baru + Upload Sampul Pertama (Baru Ditambahkan!)
    public function storeBook(Request $request)
    {
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
        $buku->status = 'Tersedia';

        if ($request->hasFile('cover')) {
            // Simpan gambar ke storage/app/public/covers
            $path = $request->file('cover')->store('covers', 'public');
            $buku->cover = $path;
        }

        $buku->save();

        return redirect()->back()->with('sukses', 'Buku baru berhasil disimpan!');
    }

    // Fungsi Pinjam
    public function prosesPinjam(Request $request)
    {
        $request->validate(['judul_buku' => 'required']);
        $buku = Book::where('judul', trim($request->judul_buku))->first();

        if ($buku && trim($buku->status) === 'Tersedia') {
            $buku->update(['status' => 'Dipinjam']);

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
            $buku->update(['status' => 'Tersedia']);
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
    
    // Fungsi Update/Ganti Sampul Buku
    public function update(Request $request, $id)
    {
        $request->validate(['cover' => 'required|image|mimes:jpeg,png,jpg|max:2048']);

        $buku = Book::findOrFail($id);  
        
        if ($request->hasFile('cover')) {
            // Hapus file sampul lama jika ada agar folder storage tidak menumpuk sampah
            if ($buku->cover && Storage::disk('public')->exists($buku->cover)) {
                Storage::disk('public')->delete($buku->cover);
            }

            // Simpan file sampul baru
            $path = $request->file('cover')->store('covers', 'public');
            $buku->cover = $path;
            $buku->save();
        }
        return redirect()->back()->with('sukses', 'Sampul berhasil diperbarui!');
    }
}