@props(['totalSop' => 0])

<div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
    <div class="absolute inset-0 bg-[url('{{ asset('images/backgrounds/hero-bg.jpg') }}')] bg-cover bg-center bg-no-repeat opacity-10 dark:opacity-5 mix-blend-multiply dark:mix-blend-overlay filter blur-[1px]"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-transparent via-white/50 to-gray-50 dark:via-dark-900/50 dark:to-dark-900"></div>
    <!-- 3D Animation Removed -->
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-brand-400/20 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
    <div class="absolute top-0 right-1/4 w-96 h-96 bg-blue-400/20 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
    <div class="absolute -bottom-32 left-1/3 w-96 h-96 bg-purple-400/20 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>
</div>

<header class="relative pt-32 pb-16 lg:pt-48 lg:pb-24 overflow-hidden z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative">
        <div data-aos="fade-down" class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-50 border border-brand-100 dark:bg-brand-900/30 dark:border-brand-800/50 mb-8 backdrop-blur-sm">
            <span class="flex h-2 w-2 relative">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-500"></span>
            </span>
            <span class="text-xs font-semibold uppercase tracking-wider text-brand-700 dark:text-brand-300">Sistem Terintegrasi v1.0</span>
        </div>

        <h1 data-aos="zoom-in-up" data-aos-duration="1000" class="text-5xl md:text-7xl lg:text-8xl font-display font-bold text-gray-900 dark:text-white mb-8 tracking-tight leading-tight">
            Portal Dokumen <br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-600 via-teal-500 to-blue-500 animate-gradient-x">Standar Operasional</span>
        </h1>

        <p data-aos="fade-up" data-aos-delay="200" class="text-lg md:text-xl text-gray-600 dark:text-gray-300 mb-12 max-w-2xl mx-auto leading-relaxed font-light">
            Akses cepat, transparan, dan akurat untuk seluruh dokumen resmi dan SOP aktif di lingkungan <span class="font-medium text-gray-900 dark:text-white">RSUP Prof. Dr. I.G.N.G. Ngoerah</span>.
        </p>

        <div data-aos="fade-up" data-aos-delay="400" class="flex flex-col sm:flex-row justify-center gap-4">
            <button @click="scrollToSection('dokumen')"
                    class="px-8 py-4 bg-gray-900 dark:bg-white text-white dark:text-gray-900 rounded-xl font-bold text-lg shadow-xl shadow-gray-900/20 hover:scale-105 hover:shadow-2xl transition-all duration-300 flex items-center justify-center gap-2 group">
                <span>Mulai Pencarian</span>
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </button>
            <button @click="scrollToSection('fitur')" class="px-8 py-4 bg-white dark:bg-white/10 text-gray-700 dark:text-white rounded-xl font-bold text-lg border border-gray-200 dark:border-white/10 hover:bg-gray-50 dark:hover:bg-white/20 transition-all duration-300 backdrop-blur-sm">
                Pelajari Fitur
            </button>
            <a href="{{ route('guide.index') }}" class="px-8 py-4 bg-brand-100 dark:bg-brand-900/30 text-brand-700 dark:text-brand-300 rounded-xl font-bold text-lg border border-brand-200 dark:border-brand-800 hover:bg-brand-200 dark:hover:bg-brand-900/50 transition-all duration-300 backdrop-blur-sm">
                Lihat Panduan
            </a>
        </div>

        <div class="mt-20 grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8 max-w-4xl mx-auto">
            <div data-aos="fade-up" data-aos-delay="500" class="glass-card p-4 rounded-2xl text-center transform hover:scale-105 transition-transform duration-300">
                <p class="text-3xl font-bold text-brand-600 dark:text-brand-400">{{ $totalSop }}+</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Dokumen Aktif</p>
            </div>
            <div data-aos="fade-up" data-aos-delay="600" class="glass-card p-4 rounded-2xl text-center transform hover:scale-105 transition-transform duration-300">
                <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">24/7</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Akses Online</p>
            </div>
                <div data-aos="fade-up" data-aos-delay="700" class="glass-card p-4 rounded-2xl text-center transform hover:scale-105 transition-transform duration-300">
                <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">100%</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Digital</p>
            </div>
                <div data-aos="fade-up" data-aos-delay="800" class="glass-card p-4 rounded-2xl text-center transform hover:scale-105 transition-transform duration-300">
                <p class="text-3xl font-bold text-amber-600 dark:text-amber-400">ISO</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Terstandarisasi</p>
            </div>
        </div>
    </div>
</header>
