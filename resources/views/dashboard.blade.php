<x-app-layout>
    <header class="bg-white py-6 mb-6">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
            <div>
                <h1 class="text-xl font-black text-slate-800 tracking-tight uppercase">
                    Perpustakaan <span class="text-pink-600">Digital</span> 
                    @if(Auth::check())
                        <span class="text-xs font-semibold text-slate-400 lowercase italic">
                            ({{ strtolower(Auth::user()->role) }})
                        </span>
                    @endif
                </h1>
            </div>
            <div class="text-right">
                <span class="text-[10px] bg-slate-100 text-slate-500 px-3 py-1.5 rounded-full font-bold uppercase">
                    {{ now()->format('d M Y') }}
                </span>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-6 mt-2">
        @if(session('sukses'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-3.5 rounded-2xl text-xs font-semibold shadow-sm mb-6">
                ✅ {{ session('sukses') }}
            </div>
        @endif
    </div>

    <div class="py-6 bg-slate-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-6 space-y-8">
            
            {{-- HEADER NAVIGASI KATEGORI --}}
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100/80 flex flex-col sm:flex-row gap-4 items-center justify-between">
                <div class="flex items-center gap-2 pl-2">
                    <span class="text-pink-600 text-sm">🎀</span>
                    <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">Kategori Rak Buku</span>
                </div>
                <div class="flex gap-1 bg-slate-50 p-1 rounded-xl">
                    <a href="/dashboard" class="text-xs font-semibold px-4 py-2 rounded-lg transition-all {{ !request('kategori') ? 'bg-white text-pink-600 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">Semua</a>
                    <a href="/dashboard?kategori=Sains" class="text-xs font-semibold px-4 py-2 rounded-lg transition-all {{ request('kategori') == 'Sains' ? 'bg-white text-pink-600 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">🔬 Sains</a>
                    <a href="/dashboard?kategori=Fiksi" class="text-xs font-semibold px-4 py-2 rounded-lg transition-all {{ request('kategori') == 'Fiksi' ? 'bg-white text-pink-600 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">📖 Fiksi</a>
                    <a href="/dashboard?kategori=Sejarah" class="text-xs font-semibold px-4 py-2 rounded-lg transition-all {{ request('kategori') == 'Sejarah' ? 'bg-white text-pink-600 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">📜 Sejarah</a>
                </div>
            </div>

            {{-- PANEL KHUSUS PENJAGA (MENGGUNAKAN STRTOLOWER AGAR AMAN) --}}
            @if(Auth::check() && strtolower(Auth::user()->role) == 'penjaga')
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm max-w-xl mx-auto w-full">
                    <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wider mb-4 text-center">📚 Tambah Buku & Sampul Baru</h3>
                    <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Judul Buku</label>
                                <input type="text" name="judul" required class="w-full rounded-xl border-slate-200 focus:border-pink-500 text-xs p-2.5">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Penulis</label>
                                <input type="text" name="penulis" required class="w-full rounded-xl border-slate-200 focus:border-pink-500 text-xs p-2.5">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Kategori</label>
                                <select name="kategori" class="w-full rounded-xl border-slate-200 focus:border-pink-500 text-xs p-2.5 bg-white">
                                    <option value="Sains">🔬 Sains</option>
                                    <option value="Fiksi">📖 Fiksi</option>
                                    <option value="Sejarah">📜 Sejarah</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Pilih File Sampul (Gambar)</label>
                                <input type="file" name="cover" required class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-pink-50 file:text-pink-700">
                            </div>
                        </div>
                        <input type="hidden" name="status" value="Tersedia">
                        <button type="submit" class="w-full py-2.5 bg-pink-600 text-white font-bold rounded-xl text-xs uppercase tracking-wider hover:bg-pink-700 transition">
                            💾 Simpan Buku Baru
                        </button>
                    </form>
                </div>
            @endif

            {{-- GRID RAK BUKU --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 w-full">
                @if(isset($semuaBuku) && $semuaBuku->count() > 0)
                    
                    @foreach($semuaBuku as $item)
                        <div class="bg-white rounded-[32px] p-5 border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between h-full">
                            
                            <div class="flex-grow flex flex-col">
                                <div class="mb-4">
                                    <span class="text-[9px] uppercase tracking-wider bg-pink-50 text-pink-600 font-bold px-3 py-1 rounded-full">
                                        {{ $item->kategori ?? 'UMUM' }}
                                    </span>
                                </div>

                                <div class="w-full h-[400px] bg-slate-100 rounded-[24px] overflow-hidden shadow-sm border border-slate-100/60 relative mb-4 flex items-center justify-center">
                                    @if($item->cover)
                                        <img src="{{ asset('storage/' . $item->cover) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="flex flex-col items-center gap-2 text-center p-6 text-slate-400">
                                            <span class="text-3xl">📖</span>
                                            <span class="text-[9px] font-bold tracking-widest uppercase">Belum Ada Sampul</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="px-2 mt-2">
                                    <h3 class="font-bold text-slate-800 text-lg leading-tight line-clamp-1" title="{{ $item->judul }}">
                                        {{ $item->judul }}
                                    </h3>
                                    <p class="text-xs text-slate-400 mt-1">by {{ $item->penulis }}</p>
                                </div>

                                {{-- FORM EDIT SAMPUL CEPAT (MENGGUNAKAN STRTOLOWER AGAR AMAN) --}}
                                @if(Auth::check() && strtolower(Auth::user()->role) == 'penjaga')
                                    <form action="{{ route('books.update', $item->id) }}" method="POST" enctype="multipart/form-data" class="mt-4 pt-3 border-t border-dashed border-slate-100">
                                        @csrf 
                                        @method('PUT')
                                        <label class="block w-full cursor-pointer bg-slate-50 hover:bg-slate-100 text-slate-600 text-[10px] text-center py-2 rounded-lg font-bold transition mb-1.5">
                                            <span id="label-file-{{$item->id}}">🖼️ Ganti Sampul</span>
                                            <input type="file" name="cover" required class="hidden" 
                                                onchange="document.getElementById('label-file-{{$item->id}}').innerText = this.files[0].name">
                                        </label>
                                        <button type="submit" class="w-full bg-pink-600 text-white text-[9px] font-bold py-1.5 rounded-lg hover:bg-pink-700 transition uppercase tracking-wider">
                                            💾 Upload
                                        </button>
                                    </form>
                                @endif
                            </div>  

                            {{-- TOMBOL AKSI UTAMA DI BAWAH (MENGGUNAKAN STRTOLOWER) --}}
                            <div class="px-2 pt-4 mt-4 border-t border-slate-50">
                                @if(Auth::check())
                                    @if(strtolower(Auth::user()->role) == 'peminjam')
                                        @if(strtolower(trim($item->status)) === 'tersedia')
                                            <form method="POST" action="{{ route('books.pinjam') }}">
                                                @csrf
                                                <input type="hidden" name="judul_buku" value="{{ $item->judul }}">
                                                <button type="submit" class="w-full py-3 bg-pink-600 hover:bg-pink-700 text-white font-bold rounded-2xl text-xs uppercase tracking-wider transition text-center block">
                                                    Pinjam Buku 💖
                                                </button>
                                            </form>
                                        @else
                                            <button disabled class="w-full py-3 bg-slate-100 text-slate-400 font-bold rounded-2xl text-xs uppercase tracking-wider cursor-not-allowed text-center block">
                                                📦 Sedang Dipinjam
                                            </button>
                                        @endif

                                    @elseif(strtolower(Auth::user()->role) == 'penjaga')
                                        @if(strtolower(trim($item->status)) === 'dipinjam')
                                            <form method="POST" action="{{ route('books.kembalikan') }}">
                                                @csrf
                                                <input type="hidden" name="judul_buku" value="{{ $item->judul }}">
                                                <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl text-xs uppercase tracking-wider transition text-center block">
                                                    ↩️ Kembalikan Buku
                                                </button>
                                            </form>
                                        @else
                                            <span class="w-full py-2 bg-emerald-50 text-emerald-600 border border-emerald-100 font-bold rounded-xl text-[10px] uppercase tracking-wider text-center block">
                                                ✓ Tersedia di Rak
                                            </span>
                                        @endif
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endforeach

                @else
                    <div class="col-span-3 py-24 text-center">
                        <h4 class="text-sm font-bold text-slate-400 uppercase tracking-widest">Rak Buku Kosong</h4>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>