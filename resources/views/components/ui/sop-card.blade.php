@props(['sop', 'index'])

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
        <a href="{{ asset('storage/' . $sop->file_path) }}" target="_blank" class="flex items-center justify-center gap-2 w-full py-3 rounded-xl font-semibold bg-gray-50 dark:bg-white/5 text-gray-700 dark:text-gray-200 hover:bg-brand-600 hover:text-white dark:hover:bg-brand-500 transition-all duration-300 group-hover:shadow-lg group-hover:shadow-brand-500/20">
            <span>Buka Dokumen</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
            </svg>
        </a>
    </div>
</div>
