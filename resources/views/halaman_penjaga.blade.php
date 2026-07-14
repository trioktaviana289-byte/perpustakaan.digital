<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjaga - Perpustakaan Ceria</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .font-plus {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex flex-col items-center justify-center p-4 md:p-6">

    <div class="bg-white p-6 md:p-8 rounded-3xl shadow-2xl shadow-slate-100 w-full max-w-3xl border border-slate-100 transition-all duration-300 hover:shadow-sky-100/50">
        
        <div class="relative flex flex-col items-center text-center pb-6 mb-6 border-b border-slate-100">
            <span class="absolute top-0 right-0 bg-sky-50 text-sky-600 text-[10px] font-extrabold tracking-widest uppercase px-3 py-1 rounded-full border border-sky-100">
                Premium Theme
            </span>
            
            <div class="w-16 h-16 bg-sky-50 rounded-2xl flex items-center justify-center mb-3 shadow-inner">
                <span class="text-3xl">🕵️‍♂️</span>
            </div>

            <h2 class="font-plus text-2xl md:text-3xl font-extrabold text-slate-850 tracking-tight flex items-center gap-2">
                Ruang Kerja Penjaga
            </h2>
            <p class="text-slate-400 text-xs md:text-sm mt-1 max-w-md leading-relaxed">
                Halaman rahasia khusus petugas untuk memantau aktivitas sirkulasi dan peminjaman buku.
            </p>
        </div>

        <div class="flex items-center justify-between mb-4">
            <h3 class="font-plus text-sm md:text-base font-bold text-slate-700 tracking-wide uppercase">
                Daftar Buku Yang Sedang Dipinjam
            </h3>
            <span class="bg-slate-100 text-slate-600 text-xs px-2.5 py-1 rounded-lg font-bold">
                {{ count($semuaPinjaman) }} Transaksi
            </span>
        </div>
        
        <div class="overflow-hidden rounded-2xl border border-slate-150 shadow-xs mb-6 bg-slate-50/50">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-sky-500/10 text-sky-800 font-plus text-xs md:text-sm font-bold tracking-wider">
                        <th class="p-4 md:px-6">Nama Teman</th>
                        <th class="p-4 md:px-6">Buku yang Dipinjam</th>
                        <th class="p-4 md:px-6 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($semuaPinjaman as $p)
                    <tr class="hover:bg-sky-50/30 bg-white transition-all duration-200">
                        <td class="p-4 md:px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center font-bold text-sm text-slate-600">
                                    {{ strtoupper(substr($p->nama_peminjam, 0, 1)) }}
                                </div>
                                <span class="font-bold text-slate-700 text-sm md:text-base">{{ $p->nama_peminjam }}</span>
                            </div>
                        </td>
                        <td class="p-4 md:px-6">
                            <div class="flex flex-col">
                                <span class="text-slate-800 font-semibold text-sm line-clamp-1">📖 {{ $p->judul_buku }}</span>
                                <span class="text-[10px] text-slate-400 mt-0.5">Sedang dibaca</span>
                            </div>
                        </td>
                        <td class="p-4 md:px-6 text-center">
                            <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 text-xs px-3 py-1 rounded-full font-bold border border-amber-200/50 shadow-2xs">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                Belum Kembali
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="p-8 text-center text-slate-400 text-sm">
                            📭 Tidak ada buku yang sedang dipinjam saat ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex justify-end pt-2">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="font-plus bg-slate-800 hover:bg-slate-900 text-white font-bold text-sm py-3 px-6 rounded-2xl shadow-lg shadow-slate-200 transition-all duration-200 transform hover:-translate-y-0.5 flex items-center gap-2 cursor-pointer">
                    Keluar Aplikasi 🚪
                </button>
            </form>
        </div>

    </div>

</body>
</html>