<footer class="bg-dark-900 text-gray-300 relative border-t border-white/5 overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-px bg-gradient-to-r from-transparent via-brand-500 to-transparent opacity-30"></div>
    <div class="absolute -top-20 -left-20 w-96 h-96 bg-brand-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-20 -right-20 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-10 relative z-10">
        <div class="grid md:grid-cols-4 gap-12 mb-16">

            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center space-x-3 mb-6">
                    <img src="{{ asset('images/logo-rs.png') }}" alt="Logo" class="h-10 w-auto rounded-lg p-1">
                    <h3 class="text-white font-display font-bold text-2xl">SIM-SOP</h3>
                </div>
                <p class="text-gray-400 leading-relaxed mb-8 max-w-md text-sm">
                    Platform resmi RSUP Prof. Dr. I.G.N.G. Ngoerah untuk pengelolaan standar operasional prosedur yang modern, akuntabel, dan transparan.
                </p>
                <div class="space-y-4 text-sm text-gray-400">
                    <div class="flex items-start gap-4">
                        <span class="p-2 bg-white/5 rounded-lg text-brand-500"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg></span>
                        <span class="mt-1">Jl. Diponegoro, Dauh Puri Klod, Denpasar, Bali.</span>
                    </div>
                        <div class="flex items-center gap-4">
                        <span class="p-2 bg-white/5 rounded-lg text-brand-500"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg></span>
                        <span>(0361) 227911</span>
                    </div>
                </div>
            </div>

            <div>
                <h4 class="text-white font-bold mb-6 text-lg">Menu Utama</h4>
                <ul class="space-y-3 text-sm text-gray-400">
                    <li><a href="{{ route('landing-page') }}" @click.prevent="scrollToTop()" class="hover:text-brand-400 transition flex items-center gap-2"><span class="w-1.5 h-1.5 bg-brand-500 rounded-full"></span> Beranda</a></li>
                    <li><button @click="scrollToSection('fitur')" class="hover:text-brand-400 transition flex items-center gap-2 text-left"><span class="w-1.5 h-1.5 bg-brand-500 rounded-full"></span> Fitur</button></li>
                    <li><button @click="scrollToSection('dokumen')" class="hover:text-brand-400 transition flex items-center gap-2 text-left"><span class="w-1.5 h-1.5 bg-brand-500 rounded-full"></span> Cari SOP</button></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-bold mb-6 text-lg">Akses Sistem</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ route('login') }}" class="block p-3 rounded-xl bg-white/5 hover:bg-brand-500 hover:text-white transition-all duration-300 border border-white/5 text-center">Masuk Aplikasi</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row justify-between items-center text-sm text-gray-500">
            <p>&copy; {{ date('Y') }} RSUP Prof. Dr. I.G.N.G. Ngoerah. All rights reserved.</p>
            <div class="mt-4 md:mt-0 flex items-center gap-2">
                <span>Made with</span>
                <svg class="w-4 h-4 text-red-500 animate-pulse" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                <span>by Instalasi SIMRS</span>
            </div>
        </div>
    </div>
</footer>
