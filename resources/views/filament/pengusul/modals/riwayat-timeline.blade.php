<div class="flex flex-col h-full space-y-4">
    {{-- Header Info SOP (Tetap) --}}
    <div class="flex-none p-4 bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Nomor SK</p>
                <p class="font-semibold text-gray-900 dark:text-white">{{ $record->nomor_sk ?? '-' }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500 dark:text-gray-400">Status Terkini</p>
                <div class="flex items-center justify-end gap-2">
                    @php $statusCurrent = strtoupper($record->status); @endphp
                    @if($statusCurrent === 'AKTIF')
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-green-100 px-3 py-1 text-sm font-bold text-green-700 dark:bg-green-500/10 dark:text-green-400 border border-green-200 dark:border-green-500/20">
                            <span class="relative flex h-2.5 w-2.5">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
                            </span>
                            AKTIF
                        </span>
                    @elseif($statusCurrent === 'KADALUARSA')
                        <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-sm font-bold text-red-700 dark:bg-red-500/10 dark:text-red-400 border border-red-200 dark:border-red-500/20">
                            KADALUARSA
                        </span>
                    @else
                        <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-sm font-bold text-gray-700 dark:bg-gray-800 dark:text-gray-400 border border-gray-200 dark:border-gray-700">
                            {{ $record->status }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Judul Timeline --}}
    <div class="flex-none flex items-center justify-between px-1">
        <p class="text-sm font-medium text-gray-700 dark:text-white flex items-center gap-1">
            <x-heroicon-o-clock class="w-4 h-4" />
            Riwayat Perubahan <span class="bg-gray-100 dark:bg-gray-800 text-xs font-bold px-2 py-0.5 rounded-full ml-1">{{ $riwayatList->count() }}</span>
        </p>
    </div>

    {{-- 
        TIMELINE CONTAINER (SCROLL AREA)
        Perubahan CSS:
        1. max-height: 60vh (Membatasi tinggi maksimal relatif terhadap viewport layar)
        2. overflow-y: auto (Wajib scroll jika konten lebih tinggi dari 60vh)
        3. overscroll-behavior: contain (Mencegah scroll induk ikut bergerak)
        4. Isolasi padding agar scrollbar rapi
    --}}
    <div class="flex-1 overflow-y-auto custom-scrollbar pr-2 -mr-2" style="max-height: 55vh; min-height: 200px;">
        @if($riwayatList->isEmpty())
            <div class="flex flex-col items-center justify-center h-48 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-lg">
                <x-heroicon-o-document-text class="w-12 h-12 mb-2 text-gray-400" />
                <p class="text-gray-500">Belum ada riwayat perubahan.</p>
            </div>
        @else
            <div class="relative pl-2 pt-2 pb-4">
                {{-- Garis Vertikal --}}
                <div class="absolute left-[19px] top-2 bottom-4 w-0.5 bg-gray-200 dark:bg-gray-700"></div>

                <div class="space-y-8">
                    @foreach($riwayatList as $index => $riwayat)
                        @php $statusSop = strtoupper($riwayat->status_sop); @endphp
                        
                        <div class="relative pl-10 group">
                            {{-- Dot Indicator --}}
                            <!-- <div @class([
                                'absolute left-0 top-1.5 w-6 h-6 rounded-full border-4 border-white dark:border-gray-900 flex items-center justify-center z-10 box-content',
                                'bg-green-500 shadow-lg shadow-green-500/30' => $statusSop === 'AKTIF',
                                'bg-red-500 shadow-lg shadow-red-500/30' => $statusSop === 'KADALUARSA',
                                'bg-gray-400' => !in_array($statusSop, ['AKTIF', 'KADALUARSA'])
                            ])></div> -->

                            {{-- Card --}}
                            <div @class([
                                'bg-white dark:bg-gray-800 rounded-xl shadow-sm border transition-all duration-200',
                                'border-l-[4px] hover:shadow-md hover:-translate-y-0.5',
                                'border-l-green-500 border-t-gray-200 border-r-gray-200 border-b-gray-200 dark:border-gray-700' => $statusSop === 'AKTIF',
                                'border-l-red-500 border-t-gray-200 border-r-gray-200 border-b-gray-200 dark:border-gray-700' => $statusSop === 'KADALUARSA',
                                'border-l-gray-400 border-t-gray-200 border-r-gray-200 border-b-gray-200 dark:border-gray-700' => !in_array($statusSop, ['AKTIF', 'KADALUARSA'])
                            ])>
                                <div class="p-4">
                                    <div class="flex items-start justify-between mb-3">
                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="font-bold text-gray-900 dark:text-white text-sm">
                                                    {{ $riwayat->user?->nama_lengkap ?? 'Sistem Otomatis' }}
                                                </span>
                                                @if($index === 0)
                                                    <span class="inline-flex items-center rounded bg-blue-50 px-1.5 py-0.5 text-[10px] font-bold text-blue-700 ring-1 ring-inset ring-blue-700/10 dark:text-blue-400 dark:ring-blue-400/30 uppercase">
                                                        Terkini
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                                {{ $riwayat->created_at->translatedFormat('d F Y') }} • {{ $riwayat->created_at->format('H:i') }}
                                            </p>
                                        </div>
                                        <span class="text-[10px] font-bold px-2 py-1 rounded border uppercase tracking-wider bg-gray-50 dark:bg-gray-700">
                                            {{ $statusSop }}
                                        </span>
                                    </div>

                                    @if($riwayat->catatan)
                                        <div class="text-xs leading-relaxed text-gray-600 dark:text-white bg-gray-50 dark:bg-gray-900 p-3 rounded-lg border border-gray-100 dark:border-gray-700">
                                            {{ $riwayat->catatan }}
                                        </div>
                                    @endif

                                    @if($riwayat->dokumen_path)
                                        <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700 flex justify-end">
                                            <a href="{{ asset('storage/' . $riwayat->dokumen_path) }}" 
                                               target="_blank"
                                               class="inline-flex items-center gap-1.5 text-xs font-bold text-primary-600 hover:text-primary-700 transition-colors">
                                                <x-heroicon-m-paper-clip class="w-3.5 h-3.5" /> Lihat Arsip
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<style>
/* CSS Scrollbar yang lebih kuat spesifisitasnya */
.custom-scrollbar {
    scrollbar-width: thin; /* Firefox */
    scrollbar-color: #cbd5e1 transparent;
}
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
    margin: 4px 0;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #cbd5e1;
    border-radius: 20px;
    border: 2px solid transparent; /* Padding effect */
    background-clip: content-box;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background-color: #94a3b8;
}
.dark .custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #475569;
}
.dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background-color: #64748b;
}
</style>