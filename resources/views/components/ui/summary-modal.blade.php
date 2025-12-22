<!-- AI Summary Modal Component -->
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
