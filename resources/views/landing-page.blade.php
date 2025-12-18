<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIM-SOP - RSUP Prof. Ngoerah</title>

    <link rel="icon" href="{{ asset('images/faviconlogo-rs.svg') }}" type="image/svg+xml">

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        display: ['"Outfit"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#ecfdf5', 100: '#d1fae5', 200: '#a7f3d0', 300: '#6ee7b7',
                            400: '#34d399', 500: '#10b981', 600: '#059669', 700: '#047857',
                            800: '#065f46', 900: '#064e3b', 950: '#022c22',
                        },
                        dark: {
                            900: '#0f172a', 800: '#1e293b', 700: '#334155',
                        }
                    },
                    animation: {
                        'blob': 'blob 7s infinite',
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        }
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        .dark .glass {
            background: rgba(15, 23, 42, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .dark .glass-card {
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        ::-webkit-scrollbar { width: 10px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 5px; border: 2px solid transparent; background-clip: content-box; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .dark ::-webkit-scrollbar-thumb { background: #475569; }
    </style>

    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-gray-50 text-gray-900 dark:bg-dark-900 dark:text-gray-100 transition-colors duration-500 overflow-x-hidden"
      x-data="appLogic()"
      @scroll.window="isScrolled = (window.pageYOffset > 20)">

    <script>
        function appLogic() {
            return {
                isScrolled: false,
                mobileMenuOpen: false,
                darkMode: false,
                isLoading: false,
                search: new URLSearchParams(window.location.search).get('search') || '',
                direktoratId: new URLSearchParams(window.location.search).get('direktorat_id') || '',
                unitId: new URLSearchParams(window.location.search).get('unit_id') || '',
                currentPath: window.location.pathname + window.location.search,

                init() {
                    this.darkMode = localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);

                    this.$watch('search', value => {
                        this.handleSearch();
                    });

                    window.addEventListener('popstate', () => {
                        const newPath = window.location.pathname + window.location.search;
                        if (newPath !== this.currentPath) {
                            this.currentPath = newPath;
                            this.search = new URLSearchParams(window.location.search).get('search') || '';
                            this.direktoratId = new URLSearchParams(window.location.search).get('direktorat_id') || '';
                            this.unitId = new URLSearchParams(window.location.search).get('unit_id') || '';
                            this.updateResults(window.location.href, false);
                        }
                    });
                },

                toggleTheme() {
                    this.darkMode = !this.darkMode;
                    localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
                    if (this.darkMode) document.documentElement.classList.add('dark');
                    else document.documentElement.classList.remove('dark');
                },

                scrollToTop() {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },
                scrollToSection(id) {
                    const el = document.getElementById(id);
                    if(el) el.scrollIntoView({ behavior: 'smooth' });
                },

                async updateResults(url = null, pushToHistory = true) {
                    this.isLoading = true;
                    if (!url) {
                        const params = new URLSearchParams();
                        if (this.search) params.append('search', this.search);
                        if (this.direktoratId) params.append('direktorat_id', this.direktoratId);
                        if (this.unitId) params.append('unit_id', this.unitId);
                        // FIX: Menggunakan nama route yang benar 'landing-page'
                        url = `{{ route('landing-page') }}?${params.toString()}`;
                    }

                    try {
                        const response = await fetch(url, {
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        const html = await response.text();
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newList = doc.getElementById('dokumen-list');

                        if (newList) {
                            document.getElementById('dokumen-list').innerHTML = newList.innerHTML;
                            setTimeout(() => { AOS.refreshHard(); }, 200);

                            if (pushToHistory) {
                                window.history.pushState({}, '', url);
                                this.currentPath = window.location.pathname + window.location.search;
                            }

                            const target = document.getElementById('dokumen');
                            if(target) {
                                const offset = 80;
                                const bodyRect = document.body.getBoundingClientRect().top;
                                const elementRect = target.getBoundingClientRect().top;
                                const elementPosition = elementRect - bodyRect;
                                const offsetPosition = elementPosition - offset;

                                if (window.pageYOffset > offsetPosition) {
                                    window.scrollTo({ top: offsetPosition, behavior: 'smooth' });
                                }
                            }
                        }
                    } catch (error) {
                        console.error('Gagal memuat data:', error);
                    } finally {
                        this.isLoading = false;
                    }
                },

                handleSearch() { this.updateResults(); },
                handleSearch() { this.updateResults(); },
                handleFilter(id) { 
                    this.direktoratId = id; 
                    this.unitId = ''; // Reset unit ketika direktorat berubah
                    this.updateResults(); 
                },
                handleFilterUnit(id) { this.unitId = id; this.updateResults(); },
                handleReset() {
                    this.search = '';
                    this.direktoratId = '';
                    this.unitId = '';
                    this.updateResults();
                },
                handleMainClick(e) {
                    const link = e.target.closest('#pagination-container a');
                    if (link && link.href) {
                        e.preventDefault();
                        this.updateResults(link.href);
                    }
                }
            }
        }
    </script>

    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute inset-0 bg-[url('{{ asset('images/backgrounds/hero-bg.jpg') }}')] bg-cover bg-center bg-no-repeat opacity-10 dark:opacity-5 mix-blend-multiply dark:mix-blend-overlay filter blur-[1px]"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-white/50 to-gray-50 dark:via-dark-900/50 dark:to-dark-900"></div>
        <div id="dna-canvas-container" class="absolute inset-0 z-10 opacity-60 mix-blend-multiply dark:mix-blend-screen"></div>
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-brand-400/20 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-0 right-1/4 w-96 h-96 bg-blue-400/20 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-32 left-1/3 w-96 h-96 bg-purple-400/20 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>
    </div>

    <nav class="fixed w-full z-50 transition-all duration-300 border-b border-transparent"
         :class="isScrolled ? 'glass shadow-lg py-2 dark:border-white/5' : 'bg-transparent py-4'">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">

                <a href="{{ route('landing-page') }}" @click.prevent="scrollToTop()" class="flex items-center space-x-3 group cursor-pointer">
                    <div class="relative">
                        <div class="absolute inset-0 bg-emerald-500 blur-lg opacity-20 rounded-full group-hover:opacity-40 transition duration-300"></div>
                        <img src="{{ asset('images/logo-rs.png') }}" alt="Logo RS" class="relative h-12 w-auto object-contain dark:bg-white/10 rounded-lg p-1 backdrop-blur-sm shadow-sm">
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 dark:text-white leading-none tracking-tight">SIM-SOP</h1>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1 font-medium group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition">RSUP Prof. Dr. I.G.N.G. Ngoerah</p>
                    </div>
                </a>

                <div class="hidden md:flex items-center space-x-1 p-1 bg-white/50 dark:bg-white/5 rounded-full backdrop-blur-md border border-gray-200/50 dark:border-white/5 shadow-sm">
                    <a href="{{ route('landing-page') }}" @click.prevent="scrollToTop()" class="px-5 py-2 text-sm font-semibold rounded-full text-brand-700 bg-brand-50 dark:text-brand-300 dark:bg-brand-900/30 transition-all cursor-pointer">Beranda</a>
                    <button @click="scrollToSection('fitur')" class="px-5 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-brand-600 dark:hover:text-brand-400 transition-colors">Fitur</button>
                    <button @click="scrollToSection('dokumen')" class="px-5 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-brand-600 dark:hover:text-brand-400 transition-colors">Cari SOP</button>
                </div>

                <div class="hidden md:flex items-center gap-4">
                    <button @click="toggleTheme()" class="w-10 h-10 rounded-full flex items-center justify-center bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 hover:border-brand-500 dark:hover:border-brand-500 transition-colors group">
                        <svg x-show="!darkMode" class="w-5 h-5 text-gray-600 group-hover:text-brand-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        <svg x-show="darkMode" x-cloak class="w-5 h-5 text-gray-300 group-hover:text-yellow-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                    </button>

                    @auth
                        <a href="{{ url('/login') }}" class="flex items-center gap-2 px-5 py-2 bg-brand-600 text-white rounded-lg font-semibold text-sm shadow-lg shadow-brand-500/30 hover:bg-brand-700 hover:-translate-y-0.5 transition-all">
                            <span>Masuk</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="flex items-center gap-2 px-5 py-2 bg-gray-900 dark:bg-white text-white dark:text-gray-900 rounded-lg font-semibold text-sm shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all">
                            <span>Masuk Aplikasi</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                        </a>
                    @endauth
                </div>

                <div class="flex items-center gap-4 md:hidden">
                    <button @click="toggleTheme()" class="p-2 text-gray-500 dark:text-gray-400">
                        <svg x-show="!darkMode" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        <svg x-show="darkMode" x-cloak class="w-6 h-6 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                    </button>
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 text-gray-700 dark:text-gray-200">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <div x-show="mobileMenuOpen" x-collapse class="md:hidden glass border-t border-gray-100 dark:border-white/5">
            <div class="px-4 py-4 space-y-2">
                <a href="#beranda" @click="mobileMenuOpen = false; scrollToTop()" class="block px-4 py-3 rounded-lg bg-gray-50 dark:bg-white/5 text-brand-700 dark:text-brand-300 font-medium">Beranda</a>
                <button @click="mobileMenuOpen = false; scrollToSection('fitur')" class="w-full text-left block px-4 py-3 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 font-medium">Fitur</button>
                <button @click="mobileMenuOpen = false; scrollToSection('dokumen')" class="w-full text-left block px-4 py-3 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 font-medium">Cari SOP</button>

                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-white/10">
                    <a href="{{ route('login') }}" class="block w-full text-center py-3 bg-brand-600 text-white rounded-xl font-bold text-sm shadow-lg">
                        @auth Dashboard Saya @else Masuk Aplikasi @endauth
                    </a>
                </div>
            </div>
        </div>
    </nav>

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
            </div>

            <div class="mt-20 grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8 max-w-4xl mx-auto">
                <div data-aos="fade-up" data-aos-delay="500" class="glass-card p-4 rounded-2xl text-center transform hover:scale-105 transition-transform duration-300">
                    <p class="text-3xl font-bold text-brand-600 dark:text-brand-400">{{ $sop_list->total() }}+</p>
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

    <section id="fitur" class="py-20 bg-gray-50 dark:bg-dark-900/50 relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 brightness-100 contrast-150 mix-blend-overlay"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div data-aos="fade-up" class="text-center mb-16">
                <span class="text-brand-600 dark:text-brand-400 font-bold tracking-widest uppercase text-sm mb-2 block">Fitur Unggulan</span>
                <h2 class="text-4xl md:text-5xl font-display font-bold text-gray-900 dark:text-white">Kenapa SIM-SOP?</h2>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div data-aos="fade-up" data-aos-delay="100" class="group p-8 rounded-3xl bg-white dark:bg-white/5 border border-gray-100 dark:border-white/5 hover:border-brand-500/50 dark:hover:border-brand-500/50 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-brand-400 to-teal-600 flex items-center justify-center mb-6 text-white shadow-lg shadow-brand-500/30 group-hover:scale-110 transition duration-300">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Arsip Digital</h3>
                    <p class="text-gray-500 dark:text-gray-400 leading-relaxed text-sm">Penyimpanan terpusat yang aman, menghilangkan risiko kehilangan dokumen fisik.</p>
                </div>
                <div data-aos="fade-up" data-aos-delay="200" class="group p-8 rounded-3xl bg-white dark:bg-white/5 border border-gray-100 dark:border-white/5 hover:border-blue-500/50 dark:hover:border-blue-500/50 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center mb-6 text-white shadow-lg shadow-blue-500/30 group-hover:scale-110 transition duration-300">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Filter Spesifik</h3>
                    <p class="text-gray-500 dark:text-gray-400 leading-relaxed text-sm">Temukan dokumen dengan mudah menggunakan filter Direktorat dan Unit Kerja yang presisi.</p>
                </div>
                <div data-aos="fade-up" data-aos-delay="300" class="group p-8 rounded-3xl bg-white dark:bg-white/5 border border-gray-100 dark:border-white/5 hover:border-purple-500/50 dark:hover:border-purple-500/50 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-purple-400 to-pink-600 flex items-center justify-center mb-6 text-white shadow-lg shadow-purple-500/30 group-hover:scale-110 transition duration-300">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Real-time Tracking</h3>
                    <p class="text-gray-500 dark:text-gray-400 leading-relaxed text-sm">Pantau status pengajuan dokumen Anda secara real-time kapan saja.</p>
                </div>
                 <div data-aos="fade-up" data-aos-delay="400" class="group p-8 rounded-3xl bg-white dark:bg-white/5 border border-gray-100 dark:border-white/5 hover:border-amber-500/50 dark:hover:border-amber-500/50 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-600 flex items-center justify-center mb-6 text-white shadow-lg shadow-amber-500/30 group-hover:scale-110 transition duration-300">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Integrasi Unit</h3>
                    <p class="text-gray-500 dark:text-gray-400 leading-relaxed text-sm">Mendukung kolaborasi lintas unit untuk SOP Antar Profesi (AP).</p>
                </div>
            </div>
        </div>
    </section>

    <div class="relative z-30 -mt-10 px-4">
        <div data-aos="fade-up" class="max-w-5xl mx-auto glass p-6 md:p-8 rounded-3xl shadow-2xl border border-white/40 dark:border-white/10 backdrop-blur-xl">
           <form class="flex flex-col lg:flex-row gap-4" @submit.prevent>
                <div class="flex-grow relative group">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                        <svg class="h-6 w-6 text-gray-400 group-focus-within:text-brand-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" x-model.debounce.500ms="search"
                           class="block w-full pl-14 pr-4 py-5 rounded-2xl bg-gray-50 dark:bg-dark-800 border-2 border-transparent focus:border-brand-500 focus:bg-white dark:focus:bg-dark-900 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none transition-all shadow-inner"
                           placeholder="Cari SOP berdasarkan judul atau nomor SK...">
                </div>

                <div class="w-full lg:w-1/4 relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false" type="button"
                            class="block w-full pl-14 pr-10 py-5 text-left text-base border-2 border-transparent bg-gray-50 dark:bg-dark-800 text-gray-900 dark:text-white rounded-2xl focus:outline-none focus:border-brand-500 focus:bg-white dark:focus:bg-dark-900 transition-all shadow-inner relative">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <span x-text="direktoratId ? document.querySelector(`option[value='${direktoratId}']`)?.text?.trim() : 'Semua Direktorat'" class="line-clamp-1"></span>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none transition-transform duration-300" :class="open ? 'rotate-180' : ''">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </button>
                    <select x-model="direktoratId" class="hidden">
                        <option value="">Semua Direktorat</option>
                        @foreach($direktorats as $dir)
                            <option value="{{ $dir->id_direktorat }}">{{ $dir->nama_direktorat }}</option>
                        @endforeach
                    </select>
                    <div x-show="open" x-transition class="absolute z-50 w-full mt-2 bg-white dark:bg-dark-800 rounded-2xl shadow-xl border border-gray-100 dark:border-white/10 max-h-80 overflow-y-auto custom-scrollbar">
                        <div class="p-2 space-y-1">
                            <div @click="handleFilter(''); open = false" class="px-4 py-3 rounded-xl cursor-pointer transition-colors" :class="!direktoratId ? 'bg-brand-50 dark:bg-brand-900/20 text-brand-700 dark:text-brand-400 font-semibold' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5'">Semua Direktorat</div>
                            @foreach($direktorats as $dir)
                            <div @click="handleFilter('{{ $dir->id_direktorat }}'); open = false" class="px-4 py-3 rounded-xl cursor-pointer transition-colors" :class="direktoratId == '{{ $dir->id_direktorat }}' ? 'bg-brand-50 dark:bg-brand-900/20 text-brand-700 dark:text-brand-400 font-semibold' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5'">{{ $dir->nama_direktorat }}</div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="w-full lg:w-1/4 relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false" type="button"
                            class="block w-full pl-14 pr-10 py-5 text-left text-base border-2 border-transparent bg-gray-50 dark:bg-dark-800 text-gray-900 dark:text-white rounded-2xl focus:outline-none focus:border-brand-500 focus:bg-white dark:focus:bg-dark-900 transition-all shadow-inner relative">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                            <svg class="h-6 w-6 text-gray-400" fill="none" class="w-6 h-6" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        </div>
                        <span x-text="unitId ? document.querySelector(`option[value='${unitId}']`)?.text?.trim() : 'Semua Unit Kerja'" class="line-clamp-1"></span>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none transition-transform duration-300" :class="open ? 'rotate-180' : ''">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </button>
                    <select x-model="unitId" class="hidden">
                        <option value="">Semua Unit Kerja</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id_unit }}">{{ $unit->nama_unit }}</option>
                        @endforeach
                    </select>
                    <div x-show="open" x-transition class="absolute z-50 w-full mt-2 bg-white dark:bg-dark-800 rounded-2xl shadow-xl border border-gray-100 dark:border-white/10 max-h-80 overflow-y-auto custom-scrollbar">
                        <div class="p-2 space-y-1">
                            <div @click="handleFilterUnit(''); open = false" class="px-4 py-3 rounded-xl cursor-pointer transition-colors" :class="!unitId ? 'bg-brand-50 dark:bg-brand-900/20 text-brand-700 dark:text-brand-400 font-semibold' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5'">Semua Unit Kerja</div>
                            @foreach($units as $unit)
                            <div x-show="!direktoratId || direktoratId == '{{ $unit->id_direktorat }}'" 
                                 @click="handleFilterUnit('{{ $unit->id_unit }}'); open = false" 
                                 class="px-4 py-3 rounded-xl cursor-pointer transition-colors" 
                                 :class="unitId == '{{ $unit->id_unit }}' ? 'bg-brand-50 dark:bg-brand-900/20 text-brand-700 dark:text-brand-400 font-semibold' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5'">
                                 {{ $unit->nama_unit }}
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div x-show="search || direktoratId || unitId" x-transition class="flex-shrink-0">
                    <button @click="handleReset()" type="button" class="w-full lg:w-auto px-6 py-5 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 font-bold rounded-2xl hover:bg-red-100 dark:hover:bg-red-900/40 transition text-center flex items-center justify-center tooltip group" title="Reset Filter">
                        <span class="mr-2 lg:hidden">Reset</span>
                        <svg class="w-6 h-6 group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </button>
                </div>
           </form>
        </div>
    </div>

    <main id="dokumen" class="relative z-10 pt-24 pb-20" @click="handleMainClick($event)">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" id="dokumen-list">

            <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6 relative">
                 <div x-show="isLoading" class="absolute inset-0 bg-gray-50/80 dark:bg-dark-900/80 z-20 flex items-center justify-center backdrop-blur-sm rounded-3xl transition-opacity duration-300">
                    <div class="flex items-center gap-3 bg-white dark:bg-dark-800 px-6 py-3 rounded-full shadow-xl">
                        <svg class="animate-spin h-5 w-5 text-brand-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">Memuat Data...</span>
                    </div>
                </div>

                <div data-aos="fade-right">
                    <h2 class="text-3xl md:text-4xl font-display font-bold text-gray-900 dark:text-white mb-3">Dokumen Terbaru</h2>
                    <p class="text-gray-600 dark:text-gray-400">Menampilkan dokumen SOP yang telah terbit dan siap diimplementasikan.</p>
                </div>
                <div data-aos="fade-left" class="bg-white dark:bg-white/5 px-6 py-3 rounded-full border border-gray-100 dark:border-white/10 shadow-sm">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Dokumen:</span>
                    <span class="ml-2 text-xl font-bold text-brand-600 dark:text-brand-400 font-display">{{ $sop_list->total() }}</span>
                </div>
            </div>

            @if($sop_list->count() > 0)
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($sop_list as $index => $sop)
                    <div data-aos="fade-up" data-aos-delay="{{ $index * 100 }}" class="group relative bg-white dark:bg-dark-800 rounded-3xl p-6 border border-gray-100 dark:border-white/5 hover:border-brand-300 dark:hover:border-brand-700/50 shadow-lg hover:shadow-2xl hover:shadow-brand-500/10 transition-all duration-500 flex flex-col h-full overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-br from-brand-50 to-transparent dark:from-brand-900/10 dark:to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>

                            <div class="relative flex justify-between items-start mb-6">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-brand-100 dark:bg-brand-900/30 flex items-center justify-center text-brand-600 dark:text-brand-400 shadow-inner">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="px-3 py-1 bg-gray-100 dark:bg-white/10 text-gray-600 dark:text-gray-300 text-xs font-bold rounded-full border border-gray-200 dark:border-white/10 uppercase tracking-wider">
                                        {{ $sop->kategori_sop }}
                                    </span>
                                </div>
                            </div>

                            <!-- Tombol Ringkas AI Minimalist -->
                             <button @click="$dispatch('open-summary', { id: '{{ $sop->id_sop }}', title: '{{ addslashes($sop->judul_sop) }}' })"
                                    class="group p-2 -mr-2 -mt-2 flex items-center gap-2 rounded-lg hover:bg-white/5 transition-all duration-300"
                                    title="Klik untuk ringkasan AI">
                                <span class="text-xs font-medium text-gray-400 group-hover:text-brand-500 dark:group-hover:text-brand-400 transition-colors uppercase tracking-wider">Ringkasan AI</span>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-brand-500 dark:group-hover:text-brand-400 transition-colors" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 9l1.25-2.75L23 5l-2.75-1.25L19 1l-1.25 2.75L15 5l2.75 1.25L19 9zm-7.5.5L9 4 6.5 9.5 1 12l5.5 2.5L9 20l2.5-5.5L17 12l-5.5-2.5zM19 15l-1.25 2.75L15 19l2.75 1.25L19 23l1.25-2.75L23 19l-2.75-1.25L19 15z"/>
                                </svg>
                            </button>
                        </div>

                        <h3 class="relative font-display font-bold text-xl text-gray-900 dark:text-white mb-4 line-clamp-2 h-14 group-hover:text-brand-600 dark:group-hover:text-brand-400 transition-colors">
                            {{ $sop->judul_sop }}
                        </h3>

                        <div class="relative space-y-3 mb-8 text-sm text-gray-500 dark:text-gray-400">
                           <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 mt-0.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                <span class="line-clamp-1">{{ $sop->unitPemilik->nama_unit ?? '-' }}</span>
                           </div>
                           <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                                <code class="bg-gray-100 dark:bg-white/5 px-2 py-0.5 rounded text-xs font-mono text-gray-600 dark:text-gray-300">{{ $sop->nomor_sk ?? 'No SK (-)' }}</code>
                           </div>
                        </div>

                        <div class="relative mt-auto pt-6 border-t border-gray-100 dark:border-white/5">
                          <!-- Button Removed from footer -->

                            <a href="{{ asset('storage/' . $sop->file_path) }}" target="_blank" class="flex items-center justify-center gap-2 w-full py-3 rounded-xl font-semibold bg-gray-50 dark:bg-white/5 text-gray-700 dark:text-gray-200 hover:bg-brand-600 hover:text-white dark:hover:bg-brand-500 transition-all duration-300 group-hover:shadow-lg group-hover:shadow-brand-500/20">
                                <span>Buka Dokumen</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-16 flex justify-center" id="pagination-container">
                    <div class="glass px-4 py-2 rounded-2xl shadow-lg border border-gray-100 dark:border-white/10 dark:text-white">
                        {{ $sop_list->links() }}
                    </div>
                </div>

            @else
                <div data-aos="zoom-in" class="text-center py-24">
                    <div class="w-24 h-24 bg-gray-100 dark:bg-white/5 rounded-full flex items-center justify-center mx-auto mb-6 animate-pulse-slow">
                        <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Tidak ditemukan</h3>
                    <p class="text-gray-500 dark:text-gray-400">Coba kata kunci lain atau reset filter pencarian Anda.</p>
                </div>
            @endif
        </div>
    </main>

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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            once: true,
            offset: 50,
            duration: 800,
            easing: 'ease-out-cubic',
        });

        // 3D DNA Animation Code (Same as previous, kept intact for functionality)
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('dna-canvas-container');
            if (!container) return;
            const scene = new THREE.Scene();
            scene.fog = new THREE.FogExp2(0xffffff, 0.05);
            const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
            const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
            renderer.setSize(window.innerWidth, window.innerHeight);
            renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
            container.appendChild(renderer.domElement);
            const dnaGroup = new THREE.Group();
            scene.add(dnaGroup);
            const count = 40;
            const geometry = new THREE.SphereGeometry(0.2, 16, 16);
            const material1 = new THREE.MeshBasicMaterial({ color: 0x10b981 });
            const material2 = new THREE.MeshBasicMaterial({ color: 0x3b82f6 });
            const lineMaterial = new THREE.LineBasicMaterial({ color: 0x94a3b8, transparent: true, opacity: 0.3 });
            for (let i = 0; i < count; i++) {
                const t = i * 0.5;
                const x = Math.cos(t) * 2;
                const z = Math.sin(t) * 2;
                const y = i * 0.4 - (count * 0.4) / 2;
                const particle1 = new THREE.Mesh(geometry, material1);
                particle1.position.set(x, y, z);
                dnaGroup.add(particle1);
                const particle2 = new THREE.Mesh(geometry, material2);
                particle2.position.set(-x, y, -z);
                dnaGroup.add(particle2);
                const points = [];
                points.push(new THREE.Vector3(x, y, z));
                points.push(new THREE.Vector3(-x, y, -z));
                const lineGeometry = new THREE.BufferGeometry().setFromPoints(points);
                const line = new THREE.Line(lineGeometry, lineMaterial);
                dnaGroup.add(line);
            }
            camera.position.z = 12;
            camera.position.y = 0;
            camera.rotation.y = 0;
            if (window.innerWidth > 768) {
                dnaGroup.position.x = 6;
                dnaGroup.rotation.z = Math.PI / 8;
            } else {
                dnaGroup.position.y = 4;
                dnaGroup.scale.set(0.7, 0.7, 0.7);
            }
            let mouseX = 0;
            let mouseY = 0;
            let targetX = 0;
            let targetY = 0;
            const windowHalfX = window.innerWidth / 2;
            const windowHalfY = window.innerHeight / 2;
            document.addEventListener('mousemove', (event) => {
                mouseX = (event.clientX - windowHalfX) * 0.001;
                mouseY = (event.clientY - windowHalfY) * 0.001;
            });
            window.addEventListener('resize', () => {
                camera.aspect = window.innerWidth / window.innerHeight;
                camera.updateProjectionMatrix();
                renderer.setSize(window.innerWidth, window.innerHeight);
                if (window.innerWidth > 768) {
                     dnaGroup.position.x = 6;
                     dnaGroup.position.y = 0;
                     dnaGroup.scale.set(1, 1, 1);
                     dnaGroup.rotation.z = Math.PI / 8;
                } else {
                     dnaGroup.position.x = 0;
                     dnaGroup.position.y = 4;
                     dnaGroup.scale.set(0.7, 0.7, 0.7);
                     dnaGroup.rotation.z = 0;
                }
            });
            const clock = new THREE.Clock();
            function animate() {
                requestAnimationFrame(animate);
                const elapsedTime = clock.getElapsedTime();
                dnaGroup.rotation.y += 0.005;
                dnaGroup.rotation.z = Math.sin(elapsedTime * 0.5) * 0.1;
                targetX = mouseX * 2;
                targetY = mouseY * 2;
                dnaGroup.rotation.x += 0.05 * (targetY - dnaGroup.rotation.x);
                dnaGroup.rotation.y += 0.05 * (targetX - dnaGroup.rotation.y);
                renderer.render(scene, camera);
            }
            animate();
        });
    </script>

    <div x-data="{ open: false, loading: false, summary: '', title: '' }"
        @keydown.escape.window="open = false"
        @open-summary.window="
            open = true;
            loading = true;
            summary = '';
            title = $event.detail.title;
            // Prevent body scroll
            document.body.style.overflow = 'hidden';
            
            fetch(`/sop/${$event.detail.id}/summarize`)
                .then(res => res.json())
                .then(data => {
                    loading = false;
                    if(data.error) {
                        summary = `<div class='p-4 bg-red-50 dark:bg-red-900/30 rounded-xl text-red-600 dark:text-red-400 text-center'>
                                        <svg class='w-8 h-8 mx-auto mb-2 opacity-50' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'/></svg>
                                        <p class='font-semibold'>${data.error}</p>
                                   </div>`;
                    } else {
                        summary = data.summary;
                    }
                })
                .catch(err => {
                    loading = false;
                    summary = `<div class='p-4 bg-red-50 dark:bg-red-900/30 rounded-xl text-red-600 dark:text-red-400 text-center font-semibold'>Gagal terhubung ke server. Periksa koneksi internet Anda.</div>`;
                });
        "
        x-init="$watch('open', value => document.body.style.overflow = value ? 'hidden' : '')"
        x-show="open"
        style="display: none;"
        class="fixed inset-0 z-[100] flex items-center justify-center p-4">

        <div x-show="open" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="open = false"
            class="absolute inset-0 bg-gray-900/70 backdrop-blur-sm transition-opacity"></div>

        <div x-show="open"
            x-transition:enter="transition ease-out duration-500 cubic-bezier(0.16, 1, 0.3, 1)"
            x-transition:enter-start="opacity-0 scale-95 translate-y-8"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-8"
            class="relative w-full max-w-2xl bg-white dark:bg-dark-800 rounded-3xl shadow-2xl overflow-hidden border border-white/20 ring-1 ring-black/5 transform">

            <!-- Decorative Header Gem -->
            <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-600"></div>

            <!-- Header -->
            <div class="px-6 py-5 border-b border-gray-100 dark:border-white/5 flex justify-between items-start bg-gray-50/50 dark:bg-white/5 backdrop-blur-xl">
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-2xl bg-brand-50 dark:bg-white/5 flex items-center justify-center">
                        <svg class="w-6 h-6 text-brand-600 dark:text-brand-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 9l1.25-2.75L23 5l-2.75-1.25L19 1l-1.25 2.75L15 5l2.75 1.25L19 9zm-7.5.5L9 4 6.5 9.5 1 12l5.5 2.5L9 20l2.5-5.5L17 12l-5.5-2.5zM19 15l-1.25 2.75L15 19l2.75 1.25L19 23l1.25-2.75L23 19l-2.75-1.25L19 15z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-display font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            Gemini AI Summary
                            <span class="px-2 py-0.5 rounded-full bg-gradient-to-r from-blue-500/10 to-purple-500/10 border border-blue-500/20 text-[10px] font-bold text-indigo-600 dark:text-indigo-400 tracking-wider uppercase">BETA</span>
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-1" x-text="title"></p>
                    </div>
                </div>
                <button @click="open = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition p-2 hover:bg-gray-100 dark:hover:bg-white/10 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Content Area -->
            <div class="p-6 md:p-8 max-h-[60vh] overflow-y-auto custom-scrollbar bg-white dark:bg-dark-800 relative">
                
                <!-- Loading State (Shimmer) -->
                <div x-show="loading" class="space-y-6 animate-pulse">
                     <div class="flex items-center space-x-4 mb-8">
                        <div class="h-4 bg-gray-200 dark:bg-gray-700/50 rounded w-3/4"></div>
                     </div>
                     <div class="space-y-3">
                        <div class="h-3 bg-gray-200 dark:bg-gray-700/50 rounded"></div>
                        <div class="h-3 bg-gray-200 dark:bg-gray-700/50 rounded w-5/6"></div>
                        <div class="h-3 bg-gray-200 dark:bg-gray-700/50 rounded w-4/6"></div>
                     </div>
                     <div class="space-y-3">
                        <div class="h-3 bg-gray-200 dark:bg-gray-700/50 rounded"></div>
                        <div class="h-3 bg-gray-200 dark:bg-gray-700/50 rounded w-5/6"></div>
                        <div class="h-3 bg-gray-200 dark:bg-gray-700/50 rounded w-4/6"></div>
                     </div>
                     <div class="flex justify-center py-6">
                        <div class="px-4 py-2 rounded-full bg-gradient-to-r from-blue-500/10 via-purple-500/10 to-pink-500/10 text-indigo-600 dark:text-indigo-400 text-xs font-semibold animate-pulse">
                            Sedang menganalisis dokumen...
                        </div>
                     </div>
                </div>

                <!-- Result -->
                <div x-show="!loading" 
                     x-transition:enter="transition ease-out duration-700"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="prose prose-lg prose-indigo dark:prose-invert max-w-none text-gray-600 dark:text-gray-300 leading-relaxed font-sans">
                        <div x-html="summary"></div>
                    </div>
                </div>

            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-50/80 dark:bg-dark-900/50 border-t border-gray-100 dark:border-white/5 flex justify-between items-center backdrop-blur-sm">
                <div class="flex items-center gap-2 opacity-60">
                    <svg class="w-4 h-4 text-gray-500" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
                    <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Powered by Google Gemini</span>
                </div>
                <button @click="open = false" class="px-6 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 rounded-xl text-sm font-bold shadow-lg hover:transform hover:-translate-y-0.5 hover:shadow-xl transition-all duration-300">
                    Selesai
                </button>
            </div>
        </div>
    </div>
</body>
</html>
