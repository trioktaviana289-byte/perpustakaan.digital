<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
    <h2 class="font-black text-2xl bg-gradient-to-r from-sky-500 via-blue-400 to-sky-650 bg-clip-text text-transparent leading-tight flex items-center gap-2">
        <span>🌻</span> {{ __('Perpustakaan Ceria') }}
    </h2>
    <span class="text-xs font-bold px-3 py-1 bg-sky-100 text-sky-700 rounded-full tracking-wider uppercase shadow-sm">
        Premium Theme
    </span>
</div>
    </x-slot>

    <div class="py-12 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-rose-50/70 via-slate-50 to-white min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            
            @if(isset($bukuTerlambat) && $bukuTerlambat->count() > 0)  
                <div class="bg-white/40 backdrop-blur-md border-l-4 border-pink-500 p-6 rounded-3xl shadow-sm border border-rose-100/80">
                    <div class="flex items-start gap-4">
                        <div class="p-3 bg-red-50 text-red-500 rounded-2xl shadow-inner animate-bounce">
                            <span>⏰</span>
                        </div>
                        <div class="w-full">
                            <h3 class="text-xs font-black text-rose-900 tracking-widest mb-3 uppercase">Pemberitahuan Pengembalian Buku</h3>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-xs text-rose-950">
                                    <thead>
                                        <tr class="border-b border-rose-200/40 opacity-70">
                                            <th class="pb-2 font-bold uppercase tracking-wider text-rose-700">Nama Peminjam</th>
                                            <th class="pb-2 font-bold uppercase tracking-wider text-rose-700">Judul Buku</th>
                                            <th class="pb-2 text-right font-bold uppercase tracking-wider text-rose-700">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-rose-100/30">
                                        @foreach($bukuTerlambat as $pinjam)
                                        <tr class="hover:bg-rose-100/20 transition-colors">
                                            <td class="py-3 font-semibold">{{ $pinjam->nama_peminjam }}</td>
                                            <td class="py-3 italic text-rose-800/90">"{{ $pinjam->judul_buku }}"</td>
                                            <td class="py-3 text-right font-bold text-pink-600 animate-pulse">Terlambat</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-2 flex flex-col sm:flex-row gap-2 items-center justify-between mb-8">
                <div class="flex items-center gap-2 pl-3 py-2 sm:py-0">
                    <span class="text-sky-500 text-sm">📚</span>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kategori Buku</span>
                </div>
                <div class="flex flex-wrap gap-1 bg-slate-50 p-1 rounded-xl w-full sm:w-auto">
                    <a href="/dashboard" class="text-xs font-semibold px-4 py-2 rounded-lg transition-all duration-200 text-center flex-1 sm:flex-none {{ !request('kategori') ? 'bg-white text-sky-600 shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900' }}">
                        Semua
                    </a>
                    <a href="/dashboard?kategori=Sains" class="text-xs font-semibold px-4 py-2 rounded-lg transition-all duration-200 text-center flex-1 sm:flex-none {{ request('kategori') == 'Sains' ? 'bg-white text-sky-600 shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900' }}">
                        🔬 Sains
                    </a>
                    <a href="/dashboard?kategori=Fiksi" class="text-xs font-semibold px-4 py-2 rounded-lg transition-all duration-200 text-center flex-1 sm:flex-none {{ request('kategori') == 'Fiksi' ? 'bg-white text-sky-600 shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900' }}">
                        📖 Fiksi
                    </a>
                    <a href="/dashboard?kategori=Sejarah" class="text-xs font-semibold px-4 py-2 rounded-lg transition-all duration-200 text-center flex-1 sm:flex-none {{ request('kategori') == 'Sejarah' ? 'bg-white text-sky-600 shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900' }}">
                        📜 Sejarah
                    </a>
                </div>
            </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 24px; margin-top: 24px; font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    @if(isset($semuaBuku) && $semuaBuku->count() > 0)
        @foreach($semuaBuku as $item)
            <div class="book-card" style="background: #ffffff; border: 1px solid #f1f5f9; border-radius: 24px; padding: 24px; text-align: center; box-shadow: 0 10px 25px -5px rgba(244, 63, 94, 0.05); display: flex; flex-direction: column; justify-content: space-between; min-height: 400px; transition: all 0.3s ease; position: relative;">
                
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; width: 100%;">
                        <span style="font-size: 10px; font-weight: 800; color: #e11d48; background-color: #fff1f2; padding: 4px 10px; border-radius: 8px; text-transform: uppercase; letter-spacing: 0.05em;">
                            {{ $item->kategori ?? 'UMUM' }}
                        </span>
                        
                        <div style="display: flex; align-items: center; gap: 6px; font-size: 11px; font-weight: 700; color: {{ $item->status == 'Tersedia' ? '#10b981' : '#94a3b8' }}">
                            <span style="width: 8px; height: 8px; border-radius: 50%; display: inline-block; background-color: {{ $item->status == 'Tersedia' ? '#10b981' : '#cbd5e1' }}"></span>
                            {{ $item->status == 'Tersedia' ? 'Ready' : 'Out' }}
                        </div>
                    </div>
                    
                    <div style="display: flex; justify-content: center; margin-bottom: 16px; width: 100%;">
                        <div style="position: relative; width: 110px; height: 160px; border-radius: 12px; box-shadow: 0 10px 20px rgba(0,0,0,0.12); overflow: hidden; background-color: #f8fafc; border: 1px solid #e2e8f0;">
                            @if(isset($item->sampul) && $item->sampul)
                                <img src="{{ asset('storage/' . $item->sampul) }}" alt="Sampul {{ $item->judul }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #0582a1, #0581a0); display: flex; flex-direction: column; justify-content: space-between; padding: 12px; box-sizing: border-box; text-align: left; color: #ffffff;">
                                    <span style="font-size: 8px; font-weight: 900; opacity: 0.85; letter-spacing: 0.05em; text-transform: uppercase;">Perpustakaan</span>
                                    <div style="font-size: 11px; font-weight: 900; line-height: 1.25; word-break: break-word; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ $item->judul }}
                                    </div>
                                    <span style="font-size: 8px; opacity: 0.9; text-transform: capitalize; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $item->penulis }}</span>
                                </div>
                            @endif

                            <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(105deg, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 50%); pointer-events: none;"></div>
                            <div style="position: absolute; top: 0; left: 0; width: 4px; bottom: 0; background: rgba(0,0,0,0.15); pointer-events: none;"></div>
                        </div>
                    </div>

                    <h3 style="font-size: 15px; font-weight: 800; color: #1e293b; margin: 0 0 4px 0; line-height: 1.3; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        {{ $item->judul }}
                    </h3>
                    
                    <p style="font-size: 12px; color: #94a3b8; margin: 0 0 16px 0;">
                        by <strong style="color: #64748b; font-weight: 600;">{{ $item->penulis }}</strong>
                    </p>
                </div>

                <div>
                    <div style="display: flex; align-items: center; justify-content: center; gap: 6px; font-size: 11px; font-weight: 700; color: {{ $item->status == 'Tersedia' ? '#047857' : '#475569' }}; margin-bottom: 16px; background-color: {{ $item->status == 'Tersedia' ? '#ecfdf5' : '#f1f5f9' }}; padding: 6px; border-radius: 12px;">
                        <span>{{ $item->status == 'Tersedia' ? '✨ Tersedia' : '📦 Dipinjam' }}</span>
                    </div>

                    @if($item->status == 'Tersedia')
                        <form method="POST" action="/pinjam-buku">
                            @csrf
                            <input type="hidden" name="judul_buku" value="{{ $item->judul }}">
                            <button type="submit" style="width: 100%; background-color: #38bdf8; color: #ffffff; border: none; padding: 12px; font-size: 12px; font-weight: 800; border-radius: 14px; letter-spacing: 0.05em; text-transform: uppercase; cursor: pointer; transition: all 0.2s;"
                                    onmouseover="this.style.backgroundColor='#0284c7'"
                                    onmouseout="this.style.backgroundColor='#7ac3e2'">
                                📥 Pinjam Buku
                            </button>
                        </form>
                    @else
                        <form method="POST" action="/kembalikan-buku">
                            @csrf
                            <input type="hidden" name="judul_buku" value="{{ $item->judul }}">
                            <button type="submit" style="width: 100%; background-color: #475569; color: #ffffff; border: none; padding: 12px; font-size: 12px; font-weight: 800; border-radius: 14px; letter-spacing: 0.05em; text-transform: uppercase; cursor: pointer; transition: all 0.2s;"
                                    onmouseover="this.style.backgroundColor='#334155'"
                                    onmouseout="this.style.backgroundColor='#475569'">
                                ↩️ Kembalikan
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    @else
        <div style="grid-column: 1 / -1; background-color: rgba(255, 255, 255, 0.5); padding: 64px 16px; border-radius: 24px; text-align: center; border: 1px solid #f1f5f9;">
            <span style="font-size: 32px; display: block; margin-bottom: 8px;">📭</span>
            <h4 style="font-size: 14px; font-weight: 800; color: #334155; margin: 0 0 2px 0;">Rak Buku Kosong</h4>
            <p style="font-size: 12px; color: #94a3b8; margin: 0;">Belum ada koleksi buku untuk kategori ini.</p>
        </div>
    @endif
</div>

<style>
    .book-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 25px -5px rgba(244, 63, 94, 0.15) !important;
    }
</style>

        </div>
    </div>
</x-app-layout>