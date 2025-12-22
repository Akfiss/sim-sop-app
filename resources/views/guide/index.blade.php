<x-layout.app>
    <x-layout.navbar />

    <div class="pt-24 pb-20 min-h-screen bg-white dark:bg-dark-900" x-data="{ 
        searchOpen: false, 
        searchTerm: '',
        lightboxOpen: false,
        lightboxImage: '',
        openLightbox(src) {
            this.lightboxImage = src;
            this.lightboxOpen = true;
        }
    }">
        <div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-12">
                
                <!-- Left Sidebar (Navigation) -->
                <aside class="w-full lg:w-64 flex-shrink-0 lg:block hidden">
                    <div class="sticky top-28 h-[calc(100vh-8rem)] overflow-y-auto pr-4 scrollbar-thin flex flex-col">
                        
                        <!-- Search Button (Sidebar) -->
                        <button @click="searchOpen = true; $nextTick(() => $refs.searchInput.focus())" class="flex items-center gap-2 w-full px-3 py-2 mb-6 bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-lg text-sm text-gray-500 hover:border-brand-500 hover:text-brand-600 transition-colors group">
                             <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                             <span>Cari panduan...</span>
                             <span class="ml-auto text-xs border border-gray-200 dark:border-white/10 rounded px-1.5 py-0.5 text-gray-400 group-hover:text-brand-500">Ctrl K</span>
                        </button>

                        <!-- Group: General -->
                        <div class="mb-8">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 px-3">General</h4>
                            <nav class="space-y-1">
                                <a href="{{ route('guide.show', 'welcome') }}" 
                                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ $role === 'welcome' ? 'text-brand-600 bg-brand-50 dark:bg-brand-900/20 dark:text-brand-400' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                                    Selamat Datang
                                </a>
                                <a href="{{ route('guide.show', 'login') }}" 
                                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ $role === 'login' ? 'text-brand-600 bg-brand-50 dark:bg-brand-900/20 dark:text-brand-400' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                                    Login Sistem
                                </a>
                                <a href="{{ route('guide.show', 'publik') }}" 
                                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ $role === 'publik' ? 'text-brand-600 bg-brand-50 dark:bg-brand-900/20 dark:text-brand-400' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                                    Akses Publik
                                </a>
                            </nav>
                        </div>

                        <!-- Group: Pengguna Internal -->
                        <div class="mb-8">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 px-3">Pengguna Internal</h4>
                            <nav class="space-y-1">
                                <a href="{{ route('guide.show', 'pengusul') }}" 
                                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ $role === 'pengusul' ? 'text-brand-600 bg-brand-50 dark:bg-brand-900/20 dark:text-brand-400' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                                    Panduan Pengusul
                                </a>
                                <a href="{{ route('guide.show', 'verifikator') }}" 
                                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ $role === 'verifikator' ? 'text-brand-600 bg-brand-50 dark:bg-brand-900/20 dark:text-brand-400' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                                    Panduan Verifikator
                                </a>
                                <a href="{{ route('guide.show', 'direksi') }}" 
                                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ $role === 'direksi' ? 'text-brand-600 bg-brand-50 dark:bg-brand-900/20 dark:text-brand-400' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                                    Panduan Direksi
                                </a>
                            </nav>
                        </div>

                         <!-- Group: Admin -->
                         <div class="mb-8">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 px-3">Administrator</h4>
                            <nav class="space-y-1">
                                <a href="{{ route('guide.show', 'admin') }}" 
                                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ $role === 'admin' ? 'text-brand-600 bg-brand-50 dark:bg-brand-900/20 dark:text-brand-400' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                                    Panduan Admin
                                </a>
                            </nav>
                        </div>

                    </div>
                </aside>

                <!-- Mobile Header for Nav -->
                <div class="lg:hidden w-full mb-6 space-y-4">
                    <button @click="searchOpen = true; $nextTick(() => $refs.searchInput.focus())" class="w-full flex items-center gap-2 px-4 py-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-sm text-gray-500 shadow-sm">
                         <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                         <span>Cari panduan...</span>
                    </button>

                    <div x-data="{ open: false }" class="bg-gray-50 dark:bg-white/5 rounded-xl border border-gray-200 dark:border-white/10">
                        <button @click="open = !open" class="w-full flex justify-between items-center px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                            <span>Menu Panduan</span>
                            <svg class="w-5 h-5 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" class="border-t border-gray-200 dark:border-white/10 p-2 space-y-1">
                             <a href="{{ route('guide.show', 'welcome') }}" class="block px-3 py-2 rounded-lg text-sm {{ $role === 'welcome' ? 'bg-brand-50 text-brand-600' : 'text-gray-600' }}">Selamat Datang</a>
                             <a href="{{ route('guide.show', 'login') }}" class="block px-3 py-2 rounded-lg text-sm {{ $role === 'login' ? 'bg-brand-50 text-brand-600' : 'text-gray-600' }}">Login Sistem</a>
                             <a href="{{ route('guide.show', 'publik') }}" class="block px-3 py-2 rounded-lg text-sm {{ $role === 'publik' ? 'bg-brand-50 text-brand-600' : 'text-gray-600' }}">Akses Publik</a>
                             <div class="border-t border-gray-100 my-1"></div>
                             <a href="{{ route('guide.show', 'pengusul') }}" class="block px-3 py-2 rounded-lg text-sm {{ $role === 'pengusul' ? 'bg-brand-50 text-brand-600' : 'text-gray-600' }}">Panduan Pengusul</a>
                             <a href="{{ route('guide.show', 'verifikator') }}" class="block px-3 py-2 rounded-lg text-sm {{ $role === 'verifikator' ? 'bg-brand-50 text-brand-600' : 'text-gray-600' }}">Panduan Verifikator</a>
                             <a href="{{ route('guide.show', 'direksi') }}" class="block px-3 py-2 rounded-lg text-sm {{ $role === 'direksi' ? 'bg-brand-50 text-brand-600' : 'text-gray-600' }}">Panduan Direksi</a>
                             <div class="border-t border-gray-100 my-1"></div>
                             <a href="{{ route('guide.show', 'admin') }}" class="block px-3 py-2 rounded-lg text-sm {{ $role === 'admin' ? 'bg-brand-50 text-brand-600' : 'text-gray-600' }}">Panduan Admin</a>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <main class="flex-grow min-w-0">
                     <div class="prose prose-lg dark:prose-invert max-w-none [&_img]:cursor-zoom-in [&_img]:rounded-lg [&_img]:transition-transform [&_img]:duration-300 hover:[&_img]:scale-[1.02] [&_img]:bg-white dark:[&_img]:bg-white/5 [&_img]:shadow-sm" 
                          @click="if($event.target.tagName === 'IMG') openLightbox($event.target.src)">
                        @include('guide.roles.' . $role)
                     </div>
                </main>

                <!-- Right Sidebar (Table of Contents) -->
                <aside class="w-64 flex-shrink-0 hidden xl:block">
                     <div class="sticky top-28">
                         <h5 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">On this page</h5>
                         <nav id="toc" class="space-y-2 border-l border-gray-200 dark:border-white/10">
                            <!-- JS will populate this -->
                         </nav>
                         <button @click="window.scrollTo({top: 0, behavior: 'smooth'})" class="mt-8 flex items-center gap-2 text-sm text-gray-500 hover:text-brand-600 transition-colors">
                             <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                             Back to top
                         </button>
                     </div>
                </aside>
            </div>
        </div>

        <!-- Search Modal -->
        <div x-show="searchOpen" 
             style="display: none;"
             class="fixed inset-0 z-[60] overflow-y-auto p-4 sm:p-6 md:p-20" 
             role="dialog" 
             aria-modal="true">
            <div class="fixed inset-0 bg-gray-500/30 dark:bg-black/50 backdrop-blur-sm transition-opacity" 
                 x-show="searchOpen"
                 x-transition.opacity
                 @click="searchOpen = false"></div>

            <div class="relative mx-auto max-w-xl transform rounded-2xl bg-white dark:bg-dark-800 shadow-2xl ring-1 ring-black/5 transition-all"
                 x-show="searchOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 @keydown.window.escape="searchOpen = false"
                 @keydown.window.ctrl.k.prevent="searchOpen = true; $nextTick(() => $refs.searchInput.focus())">
                
                <div class="relative">
                    <svg class="pointer-events-none absolute left-4 top-3.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                    </svg>
                    <input type="text" 
                           x-ref="searchInput"
                           x-model="searchTerm"
                           class="h-12 w-full border-0 bg-transparent pl-11 pr-4 text-gray-900 dark:text-gray-100 placeholder:text-gray-400 focus:ring-0 sm:text-sm" 
                           placeholder="Search docs...">
                </div>

                <!-- Results -->
                <!-- Results -->
                <ul class="max-h-80 scroll-py-2 overflow-y-auto py-2 text-sm text-gray-800 dark:text-gray-200 border-t border-gray-100 dark:border-white/10" id="options" role="listbox">
                    <template x-for="item in [
                        // General & Login
                        { title: 'Login Sistem', url: '{{ route('guide.show', 'login') }}', type: 'General', keywords: 'masuk sign in akun sso' },
                        { title: 'Lupa Password', url: '{{ route('guide.show', 'login') }}#lupa-password', type: 'General', keywords: 'reset sandi ganti password' },
                        { title: 'Akses Publik', url: '{{ route('guide.show', 'publik') }}', type: 'General', keywords: 'tamu guest tanpa login' },
                        
                        // Publik
                        { title: 'Pencarian Dokumen', url: '{{ route('guide.show', 'publik') }}#pencarian', type: 'Publik', keywords: 'cari find search filter' },
                        { title: 'Download SOP', url: '{{ route('guide.show', 'publik') }}#unduh', type: 'Publik', keywords: 'unduh simpan pdf' },
                        { title: 'Ringkasan AI', url: '{{ route('guide.show', 'publik') }}#ai-summary', type: 'Publik', keywords: 'rangkuman summary ai gemini' },

                        // Pengusul
                        { title: 'Panduan Pengusul', url: '{{ route('guide.show', 'pengusul') }}', type: 'Pengusul', keywords: 'unit kepala ruangan' },
                        { title: 'Buat Usulan Baru', url: '{{ route('guide.show', 'pengusul') }}#buat-baru', type: 'Pengusul', keywords: 'create new tambah dokumen' },
                        { title: 'Revisi Dokumen', url: '{{ route('guide.show', 'pengusul') }}#revisi', type: 'Pengusul', keywords: 'edit ubah perbaikan' },
                        { title: 'Notifikasi & Status', url: '{{ route('guide.show', 'pengusul') }}#notifikasi', type: 'Pengusul', keywords: 'email whatsapp wa status tracking' },
                        
                        // Verifikator
                        { title: 'Panduan Verifikator', url: '{{ route('guide.show', 'verifikator') }}', type: 'Verifikator', keywords: 'hukum, tkp, penjaminan mutu' },
                        { title: 'Validasi Dokumen', url: '{{ route('guide.show', 'verifikator') }}#validasi', type: 'Verifikator', keywords: 'review periksa acc setuju tolak' },
                        { title: 'Riwayat Verifikasi', url: '{{ route('guide.show', 'verifikator') }}#riwayat', type: 'Verifikator', keywords: 'history log catatan' },

                        // Direksi
                        { title: 'Panduan Direksi', url: '{{ route('guide.show', 'direksi') }}', type: 'Direksi', keywords: 'direktur pimpinan' },
                        { title: 'Dashboard Utama', url: '{{ route('guide.show', 'direksi') }}#dashboard', type: 'Direksi', keywords: 'statistik grafik chart kinerja' },
                        { title: 'Persetujuan TTE', url: '{{ route('guide.show', 'direksi') }}#tte', type: 'Direksi', keywords: 'tanda tangan elektronik sign approval' },

                        // Admin
                        { title: 'Panduan Administrator', url: '{{ route('guide.show', 'admin') }}', type: 'Administrator', keywords: 'admin it superuser' },
                        { title: 'Manajemen User', url: '{{ route('guide.show', 'admin') }}#user', type: 'Administrator', keywords: 'tambah pengguna role akun' },
                        { title: 'Master Data', url: '{{ route('guide.show', 'admin') }}#master', type: 'Administrator', keywords: 'unit kerja kategori' },
                        { title: 'Pengaturan Sistem', url: '{{ route('guide.show', 'admin') }}#settings', type: 'Administrator', keywords: 'config setting konfigurasi' }

                    ].filter(i => {
                        const term = searchTerm.toLowerCase();
                        return i.title.toLowerCase().includes(term) || (i.keywords && i.keywords.toLowerCase().includes(term));
                    })" :key="item.title + item.url">
                        <li class="group cursor-pointer select-none px-4 py-3 hover:bg-brand-50 dark:hover:bg-brand-900/20 border-b border-gray-50 dark:border-white/5 last:border-0" @click="window.location.href = item.url">
                           <div class="flex items-center gap-3">
                                <div class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center text-gray-500"
                                     :class="{
                                        'bg-blue-100 dark:bg-blue-900/30 text-blue-600': item.type === 'Pengusul',
                                        'bg-purple-100 dark:bg-purple-900/30 text-purple-600': item.type === 'Verifikator',
                                        'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600': item.type === 'Direksi',
                                        'bg-gray-100 dark:bg-white/10': item.type === 'General' || item.type === 'Admin' || item.type === 'Publik'
                                     }">
                                   
                                   <!-- Dynamic Icons based on Type -->
                                   <template x-if="item.type === 'Pengusul'"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg></template>
                                   <template x-if="item.type === 'Verifikator'"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></template>
                                   <template x-if="item.type === 'Direksi'"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2z"/></svg></template>
                                   <template x-if="['General', 'Admin', 'Publik'].includes(item.type)"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></template>
                                </div>
                                <div class="flex-grow min-w-0">
                                    <div class="font-semibold text-gray-900 dark:text-white truncate" x-text="item.title"></div>
                                    <div class="flex items-center gap-2 text-xs text-gray-500">
                                        <span class="font-medium" x-text="item.type"></span>
                                        <span x-show="item.keywords.toLowerCase().includes(searchTerm.toLowerCase()) && searchTerm.length > 2" class="text-gray-400 italic truncate hidden sm:inline">
                                            — matches "<span x-text="searchTerm"></span>"
                                        </span>
                                    </div>
                                </div>
                                <svg class="w-4 h-4 text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                           </div>
                        </li>
                    </template>
                    <li x-show="searchTerm && $el.previousElementSibling.children.length === 0" class="px-4 py-8 text-center text-gray-500">
                        No results found for "<span x-text="searchTerm"></span>"
                    </li>
                </ul>
            </div>
        </div>

        <!-- Lightbox Modal -->
        <div x-show="lightboxOpen" 
             style="display: none;"
             class="fixed inset-0 z-[70] flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm"
             x-transition.opacity
             @keydown.window.escape="lightboxOpen = false">
            
            <button @click="lightboxOpen = false" class="absolute top-6 right-6 text-white/50 hover:text-white transition-colors">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <img :src="lightboxImage" 
                 class="max-w-full max-h-[90vh] rounded-lg shadow-2xl transform transition-transform duration-300" 
                 @click.outside="lightboxOpen = false"
                 alt="Enlarged preview">
        </div>
    </div>
    </div>
    </div>

    <!-- TOC Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const content = document.querySelector('main');
            const headings = content.querySelectorAll('h2, h3');
            const toc = document.getElementById('toc');

            if (headings.length > 0 && toc) {
                headings.forEach((heading, index) => {
                    const id = heading.id || `heading-${index}`;
                    heading.id = id;

                    const link = document.createElement('a');
                    link.href = `#${id}`;
                    link.textContent = heading.innerText;
                    link.className = `block text-sm pl-4 py-1 border-l-2 border-transparent transition-colors ${
                        heading.tagName === 'H3' ? 'ml-4 text-gray-500' : 'text-gray-600 dark:text-gray-400 hover:text-brand-600 dark:hover:text-brand-400 hover:border-brand-500'
                    }`;
                    
                    // Simple active state on click
                    link.addEventListener('click', (e) => {
                        document.querySelectorAll('#toc a').forEach(el => el.classList.remove('border-brand-500', 'text-brand-600', 'font-medium'));
                        link.classList.add('border-brand-500', 'text-brand-600', 'font-medium');
                    });

                    toc.appendChild(link);
                });
            } else if (toc) {
                const empty = document.createElement('p');
                empty.textContent = '-';
                empty.className = 'text-sm text-gray-400 pl-4';
                toc.appendChild(empty);
            }
        });
    </script>
    
    <x-layout.footer />
</x-layout.app>
