<div>
    <div class="mb-12">
        <h1 class="font-display text-4xl font-bold mb-4 text-gray-900 dark:text-white">Panduan Pengguna SIM-SOP</h1>
        <p class="text-xl text-gray-600 dark:text-gray-400 leading-relaxed mb-8">
            Pusat dokumentasi dan bantuan penggunaan Sistem Informasi Manajemen Standar Operasional Prosedur RSUP Prof. NGOERAH.
        </p>
        
        <!-- Cara Menggunakan Panduan -->
         <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-2xl p-8 mb-12">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-3">
                <svg class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Cara Menggunakan Panduan
            </h2>
            
            <div class="space-y-8">
                <!-- Step 1: Search -->
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-white dark:bg-white/10 text-blue-600 dark:text-blue-400 font-bold border border-blue-100 dark:border-white/10">1</div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white mb-2">Pencarian Cepat (Global Search)</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed mb-3">
                            Gunakan kombinasi tombol <kbd class="px-2 py-0.5 rounded bg-gray-100 dark:bg-white/10 border border-gray-200 dark:border-white/20 font-mono text-xs">Ctrl</kbd> + <kbd class="px-2 py-0.5 rounded bg-gray-100 dark:bg-white/10 border border-gray-200 dark:border-white/20 font-mono text-xs">K</kbd> untuk membuka fitur pencarian. Anda dapat mencari kata kunci spesifik seperti "Notifikasi", "Revisi", atau "TTE" di semua halaman panduan.
                        </p>
                    </div>
                </div>

                <!-- Step 2: TOC -->
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-white dark:bg-white/10 text-blue-600 dark:text-blue-400 font-bold border border-blue-100 dark:border-white/10">2</div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white mb-2">Navigasi Daftar Isi (On This Page)</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                            Pada layar besar (Desktop), gunakan daftar isi di sebelah kanan layar untuk melompat cepat antar bagian di dalam satu halaman panduan.
                        </p>
                    </div>
                </div>

                 <!-- Step 3: Images -->
                 <div class="flex gap-4">
                    <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-white dark:bg-white/10 text-blue-600 dark:text-blue-400 font-bold border border-blue-100 dark:border-white/10">3</div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white mb-2">Zoom Gambar & Ilustrasi</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                            Setiap gambar tangkapan layar (screenshot) dapat diklik untuk memperbesar tampilan (Zoom). Gunakan fitur ini untuk melihat detail tombol atau menu yang dijelaskan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links Grid -->
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-16 not-prose">
        
        <!-- Akses Publik -->
        <a href="{{ route('guide.show', 'publik') }}" class="group relative overflow-hidden bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl p-6 hover:shadow-xl hover:border-brand-500/50 transition-all duration-300">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-brand-50 dark:bg-brand-900/20 rounded-full blur-2xl group-hover:bg-brand-100 dark:group-hover:bg-brand-900/40 transition-colors"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 mb-4 rounded-xl bg-brand-100 dark:bg-brand-500/20 flex items-center justify-center text-brand-600 dark:text-brand-400 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Akses Publik</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Cara mencari, memfilter, dan mengunduh dokumen publik.</p>
            </div>
        </a>

        <!-- Pengusul -->
        <a href="{{ route('guide.show', 'pengusul') }}" class="group relative overflow-hidden bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl p-6 hover:shadow-xl hover:border-blue-500/50 transition-all duration-300">
             <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-blue-50 dark:bg-blue-900/20 rounded-full blur-2xl group-hover:bg-blue-100 dark:group-hover:bg-blue-900/40 transition-colors"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 mb-4 rounded-xl bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform">
                     <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Role Pengusul</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Panduan pengajuan, revisi, dan manajemen dokumen unit.</p>
            </div>
        </a>

        <!-- Verifikator -->
        <a href="{{ route('guide.show', 'verifikator') }}" class="group relative overflow-hidden bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl p-6 hover:shadow-xl hover:border-purple-500/50 transition-all duration-300">
             <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-purple-50 dark:bg-purple-900/20 rounded-full blur-2xl group-hover:bg-purple-100 dark:group-hover:bg-purple-900/40 transition-colors"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 mb-4 rounded-xl bg-purple-100 dark:bg-purple-500/20 flex items-center justify-center text-purple-600 dark:text-purple-400 group-hover:scale-110 transition-transform">
                     <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Role Verifikator</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Prosedur validasi, review, dan persetujuan dokumen.</p>
            </div>
        </a>

        <!-- Direksi -->
        <a href="{{ route('guide.show', 'direksi') }}" class="group relative overflow-hidden bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl p-6 hover:shadow-xl hover:border-indigo-500/50 transition-all duration-300">
             <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-indigo-50 dark:bg-indigo-900/20 rounded-full blur-2xl group-hover:bg-indigo-100 dark:group-hover:bg-indigo-900/40 transition-colors"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 mb-4 rounded-xl bg-indigo-100 dark:bg-indigo-500/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition-transform">
                     <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Role Direksi</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Dashboard monitoring dan statistik kinerja unit.</p>
            </div>
        </a>
        
        <!-- Administrator -->
        <a href="{{ route('guide.show', 'admin') }}" class="group relative overflow-hidden bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl p-6 hover:shadow-xl hover:border-gray-500/50 transition-all duration-300">
             <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-gray-100 dark:bg-gray-800 rounded-full blur-2xl group-hover:bg-gray-200 dark:group-hover:bg-gray-700 transition-colors"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 mb-4 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-400 group-hover:scale-110 transition-transform">
                     <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Administrator</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Manajemen user, master data, dan konfigurasi sistem.</p>
            </div>
        </a>

        <!-- Login Guide -->
        <a href="{{ route('guide.show', 'login') }}" class="group relative overflow-hidden bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl p-6 hover:shadow-xl hover:border-green-500/50 transition-all duration-300">
             <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-green-50 dark:bg-green-900/20 rounded-full blur-2xl group-hover:bg-green-100 dark:group-hover:bg-green-900/40 transition-colors"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 mb-4 rounded-xl bg-green-100 dark:bg-green-500/20 flex items-center justify-center text-green-600 dark:text-green-400 group-hover:scale-110 transition-transform">
                     <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Halaman Login</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Cara mengakses sistem bagi pengguna terdaftar.</p>
            </div>
        </a>

    </div>

    <div class="bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl p-8 text-center">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Butuh Bantuan Teknis?</h2>
        <p class="text-gray-600 dark:text-gray-400 mb-6 max-w-2xl mx-auto">
            Jika Anda mengalami kendala teknis atau menemukan bug dalam aplikasi, silakan hubungi tim IT kami.
        </p>
        <a href="mailto:support.it@rsupngoerah.co.id" class="inline-flex items-center gap-2 px-6 py-3 bg-brand-600 hover:bg-brand-700 text-white font-medium rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            Hubungi Tim IT
        </a>
    </div>
</div>
