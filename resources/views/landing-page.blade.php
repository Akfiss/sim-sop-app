<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIM-SOP - RSUP Prof. Ngoerah</title>

    <link rel="icon" href="{{ asset('images/faviconlogo-rs.svg') }}" type="image/svg+xml">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Instrument Sans', 'sans-serif'],
                    },
                    colors: {
                        emerald: {
                            50: '#ecfdf5', 100: '#d1fae5', 200: '#a7f3d0', 300: '#6ee7b7', 400: '#34d399',
                            500: '#10b981', 600: '#059669', 700: '#047857', 800: '#065f46', 900: '#064e3b',
                        }
                    }
                }
            }
        }
    </script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Instrument Sans', sans-serif; }
        [x-cloak] { display: none !important; }

        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .dark ::-webkit-scrollbar-thumb { background: #475569; }
        .dark ::-webkit-scrollbar-thumb:hover { background: #64748b; }
    </style>

    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-gray-50 text-gray-900 dark:bg-gray-900 dark:text-gray-100 transition-colors duration-300"
      x-data="{
          isScrolled: false,
          mobileMenuOpen: false,
          darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
          toggleTheme() {
              this.darkMode = !this.darkMode;
              localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
              if (this.darkMode) {
                  document.documentElement.classList.add('dark');
              } else {
                  document.documentElement.classList.remove('dark');
              }
          }
      }"
      @scroll.window="isScrolled = (window.pageYOffset > 10)">

    <nav class="fixed w-full z-50 transition-all duration-500 ease-in-out border-b border-transparent"
         :class="isScrolled
            ? 'bg-white/70 dark:bg-gray-900/70 backdrop-blur-xl shadow-lg py-3 border-gray-200/50 dark:border-gray-800/50'
            : 'bg-transparent py-5'">
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

                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('landing-page') }}" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-emerald-600 dark:hover:text-emerald-400 transition relative group">
                        Beranda
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-emerald-500 transition-all group-hover:w-full"></span>
                    </a>
                    <a href="#fitur" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-emerald-600 dark:hover:text-emerald-400 transition relative group">
                        Fitur
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-emerald-500 transition-all group-hover:w-full"></span>
                    </a>
                    <a href="#dokumen" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-emerald-600 dark:hover:text-emerald-400 transition relative group">
                        Cari SOP
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-emerald-500 transition-all group-hover:w-full"></span>
                    </a>

                    <div class="flex items-center space-x-3 pl-6 border-l border-gray-200 dark:border-gray-700">
                        <button @click="toggleTheme()" class="p-2 rounded-full text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition mr-2 focus:outline-none ring-1 ring-gray-200 dark:ring-gray-700">
                            <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-yellow-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                            </svg>
                            <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                            </svg>
                        </button>

                        <a href="/pengusul" class="px-3 py-2 text-xs font-semibold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20 hover:bg-emerald-100 dark:hover:bg-emerald-900/40 rounded-lg transition border border-emerald-200 dark:border-emerald-800 shadow-sm hover:shadow-md transform hover:-translate-y-0.5">Pengusul</a>
                        <a href="/verifikator" class="px-3 py-2 text-xs font-semibold text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/40 rounded-lg transition border border-blue-200 dark:border-blue-800 shadow-sm hover:shadow-md transform hover:-translate-y-0.5">Verifikator</a>
                        <a href="/direksi" class="px-3 py-2 text-xs font-semibold text-purple-700 dark:text-purple-400 bg-purple-50 dark:bg-purple-900/20 hover:bg-purple-100 dark:hover:bg-purple-900/40 rounded-lg transition border border-purple-200 dark:border-purple-800 shadow-sm hover:shadow-md transform hover:-translate-y-0.5">Direksi</a>

                        <a href="/admin" class="ml-2 px-3 py-2 text-xs font-bold text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/20 hover:bg-amber-100 dark:hover:bg-amber-900/40 rounded-lg transition border border-amber-200 dark:border-amber-800 shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                            ADMIN
                        </a>
                    </div>
                </div>

                <div class="flex items-center gap-2 md:hidden">
                    <button @click="toggleTheme()" class="p-2 rounded-full text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/></svg>
                        <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    </button>
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 text-gray-600 dark:text-gray-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <div x-show="mobileMenuOpen" x-transition class="md:hidden bg-white dark:bg-gray-900 border-t border-gray-100 dark:border-gray-800 shadow-xl">
            <div class="px-4 py-4 space-y-3">
                <a href="#beranda" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded">Beranda</a>
                <a href="#fitur" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded">Fitur</a>
                <a href="#dokumen" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded">Cari SOP</a>
                <div class="border-t border-gray-100 dark:border-gray-800 my-2 pt-4 grid grid-cols-2 gap-3">
                    <a href="/pengusul" class="text-center py-2 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 rounded">Pengusul</a>
                    <a href="/verifikator" class="text-center py-2 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded">Verifikator</a>
                    <a href="/direksi" class="text-center py-2 bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 rounded">Direksi</a>
                    <a href="/admin" class="text-center py-2 bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 font-bold rounded">ADMIN</a>
                </div>
            </div>
        </div>
    </nav>

    <header class="relative pt-40 pb-20 bg-gradient-to-br from-emerald-50 via-white to-teal-50 dark:from-gray-900 dark:via-gray-800 dark:to-emerald-900/20 overflow-hidden">
        <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-bl from-teal-100/40 to-transparent dark:from-teal-900/10 pointer-events-none blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-1/3 h-2/3 bg-gradient-to-tr from-emerald-100/40 to-transparent dark:from-emerald-900/10 pointer-events-none blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 text-center relative z-10">
            <span class="inline-block py-1 px-3 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 text-xs font-semibold mb-6 tracking-wide uppercase border border-emerald-200 dark:border-emerald-800 animate-pulse">
                Sistem Terintegrasi v1.0
            </span>
            <h1 class="text-5xl md:text-7xl font-bold text-gray-900 dark:text-white mb-6 tracking-tight">
                Portal Dokumen & <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500 dark:from-emerald-400 dark:to-teal-400">Standar Operasional</span>
            </h1>
            <p class="text-lg md:text-xl text-gray-600 dark:text-gray-300 mb-10 max-w-2xl mx-auto leading-relaxed">
                Akses terpusat seluruh dokumen resmi dan SOP aktif di lingkungan RSUP Prof. Dr. I.G.N.G. Ngoerah. Transparan, Akurat, dan Terkini.
            </p>

            <div class="flex justify-center gap-4">
                <button onclick="document.getElementById('dokumen').scrollIntoView({behavior: 'smooth'})"
                        class="px-8 py-4 bg-gray-900 dark:bg-white text-white dark:text-gray-900 rounded-full font-bold shadow-xl hover:shadow-2xl hover:bg-gray-800 dark:hover:bg-gray-200 transition transform hover:-translate-y-1 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    Mulai Pencarian
                </button>
            </div>
        </div>
    </header>

    <section id="fitur" class="py-24 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-emerald-600 dark:text-emerald-400 font-semibold tracking-wider uppercase text-sm">Keunggulan Sistem</span>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mt-2 mb-4">Fitur Utama SIM-SOP</h2>
                <div class="w-20 h-1 bg-emerald-500 mx-auto rounded-full"></div>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="group p-8 rounded-2xl bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 hover:border-emerald-500 dark:hover:border-emerald-500 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center mb-6 text-white shadow-lg group-hover:scale-110 transition duration-300">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Manajemen Digital</h3>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">Arsip dokumen terpusat, aman, dan mudah diakses tanpa tumpukan kertas.</p>
                </div>
                <div class="group p-8 rounded-2xl bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-500 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center mb-6 text-white shadow-lg group-hover:scale-110 transition duration-300">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75L11.25 15 15 9.75M21 12c0 5.523-4.477 10-10 10S1 17.523 1 12 5.477 2 12 2s10 4.477 10 10z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Verifikasi Berjenjang</h3>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">Alur persetujuan sistematis dari Pengusul, Verifikator, hingga Direksi.</p>
                </div>
                <div class="group p-8 rounded-2xl bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 hover:border-purple-500 dark:hover:border-purple-500 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center mb-6 text-white shadow-lg group-hover:scale-110 transition duration-300">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Pengingat Otomatis</h3>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">Notifikasi cerdas untuk jadwal review tahunan dan masa kadaluarsa SOP.</p>
                </div>
                <div class="group p-8 rounded-2xl bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 hover:border-orange-500 dark:hover:border-orange-500 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-orange-400 to-red-500 flex items-center justify-center mb-6 text-white shadow-lg group-hover:scale-110 transition duration-300">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Kolaborasi Unit</h3>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">Mendukung SOP AP (Lintas Unit) untuk integrasi pelayanan yang lebih baik.</p>
                </div>
            </div>
        </div>
    </section>

    <div class="relative z-20 max-w-6xl mx-auto px-4 my-12">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700 backdrop-blur-sm">
            <form action="{{ route('landing-page') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-grow relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="block w-full pl-11 pr-4 py-4 border border-gray-200 dark:border-gray-700 rounded-xl leading-5 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white dark:focus:bg-gray-800 transition"
                           placeholder="Cari judul SOP, nomor SK, atau kata kunci...">
                </div>

                <div class="w-full md:w-1/3">
                    <div class="relative">
                        <select name="direktorat_id" onchange="this.form.submit()" class="block w-full pl-4 pr-10 py-4 text-base border border-gray-200 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 rounded-xl bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white appearance-none cursor-pointer">
                            <option value="">-- Semua Direktorat --</option>
                            @foreach($direktorats as $dir)
                                <option value="{{ $dir->id_direktorat }}" {{ request('direktorat_id') == $dir->id_direktorat ? 'selected' : '' }}>
                                    {{ $dir->nama_direktorat }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                @if(request('search') || request('direktorat_id'))
                    <a href="{{ route('landing-page') }}" class="px-6 py-4 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-bold rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition text-center flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                @endif
            </form>
        </div>
    </div>

    <section id="dokumen" class="py-12 bg-gray-50 dark:bg-gray-900/50 min-h-[600px] transition-colors duration-300 border-t border-gray-200 dark:border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex flex-col md:flex-row justify-between items-end mb-10 gap-4">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Daftar SOP Terbaru</h2>
                    <p class="text-gray-600 dark:text-gray-400">Dokumen yang telah terverifikasi dan berstatus <span class="font-semibold text-emerald-600 dark:text-emerald-400">AKTIF</span>.</p>
                </div>
                <div class="bg-white dark:bg-gray-800 px-4 py-2 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Dokumen:</span>
                    <span class="ml-2 text-lg font-bold text-gray-900 dark:text-white">{{ $sop_list->total() }}</span>
                </div>
            </div>

            @if($sop_list->count() > 0)
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($sop_list as $sop)
                    <div class="group bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 hover:border-emerald-400 dark:hover:border-emerald-500 hover:shadow-xl transition-all duration-300 flex flex-col h-full relative overflow-hidden">

                        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-400 to-teal-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>

                        <div class="flex justify-between items-start mb-5">
                            <div class="p-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl group-hover:bg-emerald-100 dark:group-hover:bg-emerald-900/40 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <span class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 text-xs font-bold rounded-full border border-green-200 dark:border-green-800">
                                {{ $sop->kategori_sop }}
                            </span>
                        </div>

                        <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-3 line-clamp-2 h-14 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors" title="{{ $sop->judul_sop }}">
                            {{ $sop->judul_sop }}
                        </h3>

                        <div class="space-y-3 text-sm text-gray-500 dark:text-gray-400 mb-6 border-b border-gray-100 dark:border-gray-700 pb-4">
                            <p class="flex items-center">
                                <svg class="w-4 h-4 mr-2.5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                <span class="truncate">{{ $sop->unitPemilik->nama_unit ?? 'Unit Tidak Diketahui' }}</span>
                            </p>
                            <p class="flex items-center">
                                <svg class="w-4 h-4 mr-2.5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                                <span class="font-mono text-xs bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded">{{ $sop->nomor_sk ?? 'No SK (-)' }}</span>
                            </p>
                        </div>

                        <div class="mt-auto">
                            <a href="{{ asset('storage/' . $sop->file_path) }}" target="_blank" class="w-full flex items-center justify-center space-x-2 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 py-2.5 rounded-xl hover:bg-emerald-600 hover:text-white dark:hover:bg-emerald-600 dark:hover:text-white hover:border-transparent transition-all duration-300 group-hover:shadow-md font-medium text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <span>Preview Dokumen</span>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-12 flex justify-center">
                    <div class="bg-white dark:bg-gray-800 p-2 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 dark:text-white">
                        {{ $sop_list->links() }}
                    </div>
                </div>

            @else
                <div class="text-center py-24 bg-white dark:bg-gray-800 rounded-2xl border-2 border-dashed border-gray-300 dark:border-gray-700 mx-auto max-w-2xl">
                    <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="h-10 w-10 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Data Tidak Ditemukan</h3>
                    <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto">Kami tidak dapat menemukan SOP dengan kata kunci atau filter tersebut. Silakan coba pencarian lain.</p>
                    <a href="{{ route('landing-page') }}" class="inline-block mt-6 px-6 py-2 bg-gray-900 dark:bg-white text-white dark:text-gray-900 rounded-lg font-medium hover:opacity-90 transition">
                        Reset Pencarian
                    </a>
                </div>
            @endif
        </div>
    </section>

    <footer class="bg-gray-900 text-gray-300 pt-20 pb-8 px-4 sm:px-6 lg:px-8 border-t border-gray-800">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-4 gap-12 mb-16">

                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-3 mb-6">
                        <img src="{{ asset('images/logo-rs.png') }}" alt="Logo Footer" class="h-12 w-auto rounded-lg p-1.5 shadow-lg">
                        <div>
                            <h3 class="text-white font-bold text-2xl tracking-tight">SIM-SOP</h3>
                            <p class="text-xs text-gray-400 uppercase tracking-widest mt-1">RSUP Prof. Dr. I.G.N.G. Ngoerah</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-400 leading-relaxed mb-8 max-w-md">
                        Platform manajemen dokumen standar operasional prosedur yang terintegrasi untuk mendukung pelayanan kesehatan yang bermutu, aman, dan terstandarisasi sesuai akreditasi.
                    </p>
                    <div class="space-y-4 text-sm">
                        <div class="flex items-start group">
                            <div class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center mr-3 group-hover:bg-emerald-600 transition duration-300">
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <span class="mt-1.5">Jl. Diponegoro, Dauh Puri Klod, Denpasar Barat, Bali 80114</span>
                        </div>
                        <div class="flex items-center group">
                            <div class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center mr-3 group-hover:bg-emerald-600 transition duration-300">
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                            <span>(0361) 227911</span>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="text-white font-bold mb-6 text-lg">Navigasi</h4>
                    <ul class="space-y-4 text-sm text-gray-400">
                        <li><a href="{{ route('landing-page') }}" class="hover:text-emerald-400 transition flex items-center group"><span class="w-2 h-2 bg-gray-700 rounded-full mr-3 group-hover:bg-emerald-500 transition"></span> Beranda</a></li>
                        <li><a href="#fitur" class="hover:text-emerald-400 transition flex items-center group"><span class="w-2 h-2 bg-gray-700 rounded-full mr-3 group-hover:bg-emerald-500 transition"></span> Fitur Aplikasi</a></li>
                        <li><a href="#dokumen" class="hover:text-emerald-400 transition flex items-center group"><span class="w-2 h-2 bg-gray-700 rounded-full mr-3 group-hover:bg-emerald-500 transition"></span> Cari SOP</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-bold mb-6 text-lg">Akses Portal</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="/admin" class="flex items-center p-2 rounded-lg bg-gray-800 hover:bg-gray-700 transition group"><span class="mr-3 text-xl group-hover:scale-110 transition">🔐</span> Login Admin</a></li>
                        <li><a href="/pengusul" class="flex items-center p-2 rounded-lg bg-gray-800 hover:bg-gray-700 transition group"><span class="mr-3 text-xl group-hover:scale-110 transition">📝</span> Login Pengusul</a></li>
                        <li><a href="/verifikator" class="flex items-center p-2 rounded-lg bg-gray-800 hover:bg-gray-700 transition group"><span class="mr-3 text-xl group-hover:scale-110 transition">✅</span> Login Verifikator</a></li>
                        <li><a href="/direksi" class="flex items-center p-2 rounded-lg bg-gray-800 hover:bg-gray-700 transition group"><span class="mr-3 text-xl group-hover:scale-110 transition">📊</span> Login Direksi</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center text-sm text-gray-500">
                <p>&copy; {{ date('Y') }} RSUP Prof. Dr. I.G.N.G. Ngoerah.</p>
                <div class="mt-4 md:mt-0">
                    <span class="opacity-75">Developed by</span> <span class="text-emerald-500 font-semibold">IT Instalation</span>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
