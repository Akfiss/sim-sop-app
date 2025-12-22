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

                <div class="hidden md:flex items-center relative p-1 bg-white/50 dark:bg-white/5 rounded-full backdrop-blur-md border border-gray-200/50 dark:border-white/5 shadow-sm"
                     x-data="{ 
                        active: '{{ request()->routeIs('guide.*') ? 'panduan' : 'beranda' }}', 
                        rect: { left: 4, width: 85 },
                        updateRect(el) {
                            if(el) {
                                this.rect.left = el.offsetLeft;
                                this.rect.width = el.offsetWidth;
                            }
                        },
                        init() {
                            this.$nextTick(() => {
                                const el = this.$refs[this.active];
                                if(el) this.updateRect(el);
                            });
                        }
                     }"
                     @resize.window="updateRect($refs[active])">
                    
                    <!-- Sliding Pill Background -->
                    <div class="absolute top-1 bottom-1 rounded-full bg-brand-50 dark:bg-brand-900/30 border border-brand-100 dark:border-brand-800/50 transition-all duration-300 ease-out z-0"
                         :style="`left: ${rect.left}px; width: ${rect.width}px`"></div>

                    <!-- Menu Items -->
                    <a href="{{ route('landing-page') }}" 
                       x-ref="beranda"
                       @click="
                            active = 'beranda'; 
                            updateRect($el); 
                            if(window.location.pathname === '/') { $event.preventDefault(); scrollToTop(); }
                       "
                       class="relative z-10 px-5 py-2 text-sm font-semibold rounded-full transition-colors duration-300"
                       :class="active === 'beranda' ? 'text-brand-700 dark:text-brand-300' : 'text-gray-600 dark:text-gray-300 hover:text-brand-600 dark:hover:text-brand-400'">
                       Beranda
                    </a>

                    <button x-ref="fitur"
                            @click="active = 'fitur'; updateRect($el); scrollToSection('fitur')"
                            class="relative z-10 px-5 py-2 text-sm font-semibold rounded-full transition-colors duration-300"
                            :class="active === 'fitur' ? 'text-brand-700 dark:text-brand-300' : 'text-gray-600 dark:text-gray-300 hover:text-brand-600 dark:hover:text-brand-400'">
                        Fitur
                    </button>

                    <button x-ref="cari"
                            @click="active = 'cari'; updateRect($el); scrollToSection('dokumen')"
                            class="relative z-10 px-5 py-2 text-sm font-semibold rounded-full transition-colors duration-300"
                            :class="active === 'cari' ? 'text-brand-700 dark:text-brand-300' : 'text-gray-600 dark:text-gray-300 hover:text-brand-600 dark:hover:text-brand-400'">
                        Cari SOP
                    </button>

                    <a href="{{ route('guide.index') }}" 
                       x-ref="panduan"
                       @click="active = 'panduan'; updateRect($el)"
                       class="relative z-10 px-5 py-2 text-sm font-semibold rounded-full transition-colors duration-300"
                       :class="active === 'panduan' ? 'text-brand-700 dark:text-brand-300' : 'text-gray-600 dark:text-gray-300 hover:text-brand-600 dark:hover:text-brand-400'">
                       Panduan
                    </a>
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
                <a href="{{ route('landing-page') }}" class="block px-4 py-3 rounded-lg {{ request()->routeIs('landing-page') ? 'bg-brand-50 text-brand-600 dark:bg-brand-900/30 dark:text-brand-300' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5' }} font-medium">Beranda</a>
                <button @click="mobileMenuOpen = false; scrollToSection('fitur')" class="w-full text-left block px-4 py-3 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 font-medium">Fitur</button>
                <button @click="mobileMenuOpen = false; scrollToSection('dokumen')" class="w-full text-left block px-4 py-3 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 font-medium">Cari SOP</button>
                <a href="{{ route('guide.index') }}" class="block px-4 py-3 rounded-lg {{ request()->routeIs('guide.*') ? 'bg-brand-50 text-brand-600 dark:bg-brand-900/30 dark:text-brand-300' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5' }} font-medium">Panduan</a>

                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-white/10">
                    <a href="{{ route('login') }}" class="block w-full text-center py-3 bg-brand-600 text-white rounded-xl font-bold text-sm shadow-lg">
                        @auth Dashboard Saya @else Masuk Aplikasi @endauth
                    </a>
                </div>
            </div>
        </div>
    </nav>
