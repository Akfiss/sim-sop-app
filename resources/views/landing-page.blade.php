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
                    },
                    backgroundImage: {
                        'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
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
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 10px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 5px; border: 2px solid transparent; background-clip: content-box; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .dark ::-webkit-scrollbar-thumb { background: #475569; }
        .dark ::-webkit-scrollbar-thumb:hover { background: #64748b; }
    </style>

    <script>
        // Init Dark Mode
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
                
                init() {
                    this.darkMode = localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);
                    
                    // Handle browser back/forward buttons
                    window.addEventListener('popstate', () => {
                        this.updateResults(window.location.href, false);
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

                // --- LOGIC FETCH AJAX UTAMA ---
                async updateResults(url = null, pushToHistory = true) {
                    this.isLoading = true;
                    
                    // Bangun URL jika tidak diberikan
                    if (!url) {
                         const params = new URLSearchParams();
                        if (this.search) params.append('search', this.search);
                        if (this.direktoratId) params.append('direktorat_id', this.direktoratId);
                        url = `{{ route('landing-page') }}?${params.toString()}`;
                    }

                    try {
                        const response = await fetch(url, {
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        const html = await response.text();
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        
                        // Replace Konten Daftar SOP (Termasuk Pagination di dalamnya)
                        const newList = doc.getElementById('dokumen-list');
                        if (newList) {
                            document.getElementById('dokumen-list').innerHTML = newList.innerHTML;
                            
                            // Penting: Refresh AOS agar animasi ulang (dan tidak hidden)
                            setTimeout(() => {
                                AOS.refreshHard(); 
                            }, 200);

                            if (pushToHistory) {
                                window.history.pushState({}, '', url);
                            }
                            
                            // Scroll ke bagian daftar dokumen (bukan top page)
                            const target = document.getElementById('dokumen');
                            const offset = 80; // Navbar offset
                            const bodyRect = document.body.getBoundingClientRect().top;
                            const elementRect = target.getBoundingClientRect().top;
                            const elementPosition = elementRect - bodyRect;
                            const offsetPosition = elementPosition - offset;

                            window.scrollTo({
                                top: offsetPosition,
                                behavior: 'smooth'
                            });
                        }

                    } catch (error) {
                        console.error('Gagal memuat data:', error);
                    } finally {
                        this.isLoading = false;
                    }
                },

                handleSearch() {
                    this.updateResults();
                },

                handleFilter(id) {
                    this.direktoratId = id;
                    this.updateResults();
                },

                handleReset() {
                    this.search = '';
                    this.direktoratId = '';
                    this.updateResults();
                },
                
                // Handler Klik Pagination (Mencegah reload full)
                handleMainClick(e) {
                    // Cek apakah yang diklik adalah link pagination
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
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-brand-400/20 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-0 right-1/4 w-96 h-96 bg-blue-400/20 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-32 left-1/3 w-96 h-96 bg-purple-400/20 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>
    </div>

    <nav class="fixed w-full z-50 transition-all duration-300 border-b border-transparent"
         :class="isScrolled ? 'glass shadow-lg py-2 dark:border-white/5' : 'bg-transparent py-4'">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                
                <a href="{{ route('landing-page') }}" class="flex items-center space-x-3 group cursor-pointer">
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
                    <a href="{{ route('landing-page') }}" class="px-5 py-2 text-sm font-semibold rounded-full text-brand-700 bg-brand-50 dark:text-brand-300 dark:bg-brand-900/30 transition-all cursor-pointer">Beranda</a>
                    <a href="#fitur" class="px-5 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-brand-600 dark:hover:text-brand-400 transition-colors">Fitur</a>
                    <a href="#dokumen" class="px-5 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-brand-600 dark:hover:text-brand-400 transition-colors">Cari SOP</a>
                </div>

                <div class="hidden md:flex items-center gap-4">
                    <button @click="toggleTheme()" class="w-10 h-10 rounded-full flex items-center justify-center bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 hover:border-brand-500 dark:hover:border-brand-500 transition-colors group">
                        <svg x-show="!darkMode" class="w-5 h-5 text-gray-600 group-hover:text-brand-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        <svg x-show="darkMode" x-cloak class="w-5 h-5 text-gray-300 group-hover:text-yellow-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                    </button>

                    <div class="relative group" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                        <button class="flex items-center gap-2 px-4 py-2 bg-gray-900 dark:bg-white text-white dark:text-gray-900 rounded-lg font-semibold text-sm shadow-lg hover:shadow-xl hover:translate-y-[-2px] transition-all">
                            <span>Akses Portal</span>
                            <svg class="w-4 h-4 transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" x-transition class="absolute right-0 mt-2 w-48 bg-white dark:bg-dark-800 rounded-xl shadow-xl border border-gray-100 dark:border-white/10 py-2 z-50">
                            <a href="/pengusul" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-brand-50 dark:hover:bg-brand-900/20 hover:text-brand-600 transition-colors">Login Pengusul</a>
                            <a href="/verifikator" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-brand-50 dark:hover:bg-brand-900/20 hover:text-brand-600 transition-colors">Login Verifikator</a>
                            <a href="/direksi" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-brand-50 dark:hover:bg-brand-900/20 hover:text-brand-600 transition-colors">Login Direksi</a>
                            <div class="h-px bg-gray-100 dark:bg-white/10 my-1"></div>
                            <a href="/admin" class="block px-4 py-2 text-sm font-bold text-brand-600 dark:text-brand-400 hover:bg-brand-50 dark:hover:bg-brand-900/20 transition-colors">Login Admin</a>
                        </div>
                    </div>
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
                <a href="{{ route('landing-page') }}" class="block px-4 py-3 rounded-lg bg-gray-50 dark:bg-white/5 text-brand-700 dark:text-brand-300 font-medium">Beranda</a>
                <a href="#fitur" @click="mobileMenuOpen = false" class="block px-4 py-3 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 font-medium">Fitur</a>
                <a href="#dokumen" @click="mobileMenuOpen = false" class="block px-4 py-3 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 font-medium">Cari SOP</a>
                <div class="grid grid-cols-2 gap-2 mt-4 pt-4 border-t border-gray-100 dark:border-white/10">
                    <a href="/pengusul" class="text-center py-2.5 bg-brand-50 dark:bg-brand-900/20 text-brand-700 dark:text-brand-400 rounded-lg text-sm font-semibold">Pengusul</a>
                    <a href="/verifikator" class="text-center py-2.5 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 rounded-lg text-sm font-semibold">Verifikator</a>
                    <a href="/direksi" class="text-center py-2.5 bg-purple-50 dark:bg-purple-900/20 text-purple-700 dark:text-purple-400 rounded-lg text-sm font-semibold">Direksi</a>
                    <a href="/admin" class="text-center py-2.5 bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400 rounded-lg text-sm font-bold">ADMIN</a>
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
                <button onclick="document.getElementById('dokumen').scrollIntoView({behavior: 'smooth'})" 
                        class="px-8 py-4 bg-gray-900 dark:bg-white text-white dark:text-gray-900 rounded-xl font-bold text-lg shadow-xl shadow-gray-900/20 hover:scale-105 hover:shadow-2xl transition-all duration-300 flex items-center justify-center gap-2 group">
                    <span>Mulai Pencarian</span>
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </button>
                <a href="#fitur" class="px-8 py-4 bg-white dark:bg-white/10 text-gray-700 dark:text-white rounded-xl font-bold text-lg border border-gray-200 dark:border-white/10 hover:bg-gray-50 dark:hover:bg-white/20 transition-all duration-300 backdrop-blur-sm">
                    Pelajari Fitur
                </a>
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
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Verifikasi Online</h3>
                    <p class="text-gray-500 dark:text-gray-400 leading-relaxed text-sm">Alur persetujuan yang sistematis dan tercatat dari unit hingga direksi.</p>
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
                    <input type="text" x-model.debounce.500ms="search" @input="handleSearch"
                           class="block w-full pl-14 pr-4 py-5 rounded-2xl bg-gray-50 dark:bg-dark-800 border-2 border-transparent focus:border-brand-500 focus:bg-white dark:focus:bg-dark-900 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none transition-all shadow-inner"
                           placeholder="Cari SOP berdasarkan judul atau nomor SK...">
                </div>

                <div class="w-full lg:w-1/3 relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false" type="button" 
                            class="block w-full pl-14 pr-10 py-5 text-left text-base border-2 border-transparent bg-gray-50 dark:bg-dark-800 text-gray-900 dark:text-white rounded-2xl focus:outline-none focus:border-brand-500 focus:bg-white dark:focus:bg-dark-900 transition-all shadow-inner relative">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <span x-text="direktoratId ? document.querySelector(`option[value='${direktoratId}']`)?.text?.trim() : 'Semua Direktorat'"></span>
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
                            <div @click="handleFilter(''); open = false" 
                                 class="px-4 py-3 rounded-xl cursor-pointer transition-colors"
                                 :class="!direktoratId ? 'bg-brand-50 dark:bg-brand-900/20 text-brand-700 dark:text-brand-400 font-semibold' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5'">
                                Semua Direktorat
                            </div>
                            @foreach($direktorats as $dir)
                            <div @click="handleFilter('{{ $dir->id_direktorat }}'); open = false" 
                                 class="px-4 py-3 rounded-xl cursor-pointer transition-colors"
                                 :class="direktoratId == '{{ $dir->id_direktorat }}' ? 'bg-brand-50 dark:bg-brand-900/20 text-brand-700 dark:text-brand-400 font-semibold' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5'">
                                {{ $dir->nama_direktorat }}
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div x-show="search || direktoratId" x-transition class="flex-shrink-0">
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
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Arsip:</span>
                    <span class="ml-2 text-xl font-bold text-brand-600 dark:text-brand-400 font-display">{{ $sop_list->total() }}</span>
                </div>
            </div>

            @if($sop_list->count() > 0)
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($sop_list as $index => $sop)
                    <div data-aos="fade-up" data-aos-delay="{{ $index * 100 }}" class="group relative bg-white dark:bg-dark-800 rounded-3xl p-6 border border-gray-100 dark:border-white/5 hover:border-brand-300 dark:hover:border-brand-700/50 shadow-lg hover:shadow-2xl hover:shadow-brand-500/10 transition-all duration-500 flex flex-col h-full overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-br from-brand-50 to-transparent dark:from-brand-900/10 dark:to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>

                        <div class="relative flex justify-between items-start mb-6">
                            <div class="w-12 h-12 rounded-2xl bg-brand-100 dark:bg-brand-900/30 flex items-center justify-center text-brand-600 dark:text-brand-400 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300 shadow-inner">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <span class="px-3 py-1 bg-gray-100 dark:bg-white/10 text-gray-600 dark:text-gray-300 text-xs font-bold rounded-full border border-gray-200 dark:border-white/10 group-hover:bg-brand-500 group-hover:text-white group-hover:border-brand-500 transition-colors uppercase tracking-wider">
                                {{ $sop->kategori_sop }}
                            </span>
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
                        <li><a href="{{ route('landing-page') }}" class="hover:text-brand-400 transition flex items-center gap-2"><span class="w-1.5 h-1.5 bg-brand-500 rounded-full"></span> Beranda</a></li>
                        <li><a href="#fitur" class="hover:text-brand-400 transition flex items-center gap-2"><span class="w-1.5 h-1.5 bg-brand-500 rounded-full"></span> Fitur</a></li>
                        <li><a href="#dokumen" class="hover:text-brand-400 transition flex items-center gap-2"><span class="w-1.5 h-1.5 bg-brand-500 rounded-full"></span> Cari SOP</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-bold mb-6 text-lg">Akses Sistem</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="/pengusul" class="block p-3 rounded-xl bg-white/5 hover:bg-brand-500 hover:text-white transition-all duration-300 border border-white/5 text-center">Login Pengusul</a></li>
                         <li><a href="/verifikator" class="block p-3 rounded-xl bg-white/5 hover:bg-blue-600 hover:text-white transition-all duration-300 border border-white/5 text-center">Login Verifikator</a></li>
                         <li><a href="/direksi" class="block p-3 rounded-xl bg-white/5 hover:bg-purple-600 hover:text-white transition-all duration-300 border border-white/5 text-center">Login Direksi</a></li>
                         <li><a href="/admin" class="flex items-center gap-2 text-gray-400 hover:text-white transition justify-end mt-4"><span class="text-xs font-mono">ADMIN ACCESS</span> <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg></a></li>
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

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            once: true,
            offset: 50,
            duration: 800,
            easing: 'ease-out-cubic',
        });
    </script>
</body>
</html>