<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Masuk Perpustakaan Ceria</title>
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    </head>
    <body class="min-h-screen flex flex-col sm:justify-center items-center bg-sky-100">
        
        <div class="min-h-screen w-full bg-sky-100 flex selection:bg-sky-500 selection:text-white relative items-center justify-center">
            
            <div class="flex-1 flex flex-col justify-center items-center p-6 sm:p-10 md:max-w-xl z-10">
                
                <div class="w-full max-w-[400px] bg-white p-8 rounded-3xl border border-sky-200/60 shadow-[0_20px_50px_rgba(56,189,248,0.35)] hover:shadow-[0_20px_50px_rgba(56,189,248,0.5)] transition-all duration-300">
                    
                    <div class="flex flex-col items-start mb-8">
                        <div class="w-12 h-12 bg-sky-50 rounded-2xl flex items-center justify-center mb-4 border border-sky-100/50">
                            <span class="text-2xl">📚</span>
                        </div>
                        <h1 class="text-3xl font-black text-slate-800 tracking-tight">Selamat Datang!</h1>
                        <p class="text-xs text-slate-400 mt-1.5 font-medium leading-relaxed">Silakan masuk untuk menjelajahi koleksi buku dan memantau aktivitas.</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <div class="space-y-1.5">
                            <label for="email" class="text-[11px] font-bold text-slate-400 uppercase tracking-wider pl-1">Alamat Email</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 text-xs">✉️</span>
                                <input id="email" type="email" name="email" placeholder="nama@email.com" required autofocus
                                    class="w-full pl-10 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-50 transition-all duration-200 placeholder:text-slate-300 font-medium text-slate-700" />
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label for="password" class="text-[11px] font-bold text-slate-400 uppercase tracking-wider pl-1">Kata Sandi</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 text-xs">🔒</span>
                                <input id="password" type="password" name="password" placeholder="••••••••" required 
                                    class="w-full pl-10 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-400 focus:bg-white focus:ring-4 focus:ring-sky-50 transition-all duration-200 placeholder:text-slate-300 text-slate-700" />
                            </div>
                        </div>

                        <div class="flex items-center pt-1">
                            <label class="flex items-center cursor-pointer group">
                                <input type="checkbox" name="remember" class="w-4 h-4 rounded text-sky-400 border-slate-300 focus:ring-sky-300 cursor-pointer transition-colors">
                                <span class="ml-2.5 text-xs font-semibold text-slate-400 group-hover:text-slate-600 transition-colors">Biarkan saya tetap masuk</span>
                            </label>
                        </div>

                        <button type="submit" style="background-color: #38bdf8 !important;"
                            class="w-full py-3.5 text-white font-extrabold rounded-xl text-xs tracking-widest uppercase shadow-lg shadow-sky-200/50 hover:opacity-95 hover:shadow-sky-300/50 active:scale-[0.98] transition-all duration-200 cursor-pointer text-center block">
                            Masuk Sekarang
                        </button>
                    </form>

                    <div class="relative flex py-4 items-center my-5">
                        <div class="flex-grow border-t border-slate-100"></div>
                        <span class="flex-shrink mx-4 text-[10px] font-bold text-slate-300 tracking-widest uppercase">Atau</span>
                        <div class="flex-grow border-t border-slate-100"></div>
                    </div>

                    <div class="text-center">
                        <a href="{{ route('register') }}" class="text-xs font-bold text-sky-400 hover:text-sky-500 transition-colors">
                            Belum punya akun? Bikin di sini
                        </a>
                    </div>

                </div>
            </div>

          
        
        
    </body>
</html>