<x-layout.app>
    <x-layout.navbar />

    <x-sections.hero :total-sop="$sop_list->total()" />

    <x-sections.features />

    <x-sections.flow />

    <x-ui.sop-search :direktorats="$direktorats" :units="$units" />

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
                        <x-ui.sop-card :sop="$sop" :index="$index" />
                    @endforeach
                </div>

                <div class="mt-16 flex justify-center" id="pagination-container">
                    <div class="glass px-4 py-2 rounded-2xl shadow-lg border border-gray-100 dark:border-white/10 dark:text-white">
                        {{ $sop_list->links() }}
                    </div>
                </div>

            @else
                <x-ui.empty-state />
            @endif
        </div>
    </main>

    <x-layout.footer />

    <x-ui.summary-modal />
</x-layout.app>
