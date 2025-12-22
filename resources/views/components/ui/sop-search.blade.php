@props(['direktorats', 'units'])

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
