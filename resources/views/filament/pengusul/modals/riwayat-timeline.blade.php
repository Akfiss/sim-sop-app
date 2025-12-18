<div class="space-y-6">
    {{-- Header Info SOP --}}
    <div class="p-4 bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Nomor SK</p>
                <p class="font-semibold text-gray-900 dark:text-white">{{ $record->nomor_sk ?? '-' }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500 dark:text-gray-400">Status Terkini</p>
                <span @class([
                    'inline-flex items-center rounded-full px-3 py-1 text-sm font-medium',
                    'bg-red-100 text-red-800' => $record->status === 'KADALUARSA',
                    'bg-green-100 text-green-800' => $record->status === 'AKTIF',
                ])>
                    {{ $record->status }}
                </span>
            </div>
        </div>
    </div>

    {{-- Timeline Riwayat --}}
    <div class="relative">
        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">
            <x-heroicon-o-clock class="w-4 h-4 inline mr-1" />
            Total {{ $riwayatList->count() }} perubahan tercatat
        </p>

        @if($riwayatList->isEmpty())
            <div class="text-center py-8 text-gray-500">
                <x-heroicon-o-document-text class="w-12 h-12 mx-auto mb-2 text-gray-300" />
                <p>Belum ada riwayat perubahan untuk SOP ini.</p>
            </div>
        @else
            {{-- Timeline --}}
            <div class="relative border-l-2 border-gray-200 dark:border-gray-700 ml-4">
                @foreach($riwayatList as $index => $riwayat)
                    <div class="mb-6 ml-6 relative">
                        {{-- Dot Indicator --}}
                        <span @class([
                            'absolute -left-9 flex items-center justify-center w-6 h-6 rounded-full ring-4 ring-gray-900 dark:ring-gray-900',
                            'bg-green-500' => $riwayat->status_sop === 'AKTIF',
                            'bg-red-500' => $riwayat->status_sop === 'KADALUARSA',
                        ])>
                            @if($riwayat->status_sop === 'AKTIF')
                                <x-heroicon-s-check class="w-3 h-3 text-white" />
                            @else
                                <x-heroicon-s-x-mark class="w-3 h-3 text-white" />
                            @endif
                        </span>

                        {{-- Content Card --}}
                        <div @class([
                            'p-4 rounded-lg border',
                            'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700',
                            'ring-2 ring-green-500/20' => $riwayat->status_sop === 'AKTIF',
                            'ring-2 ring-red-500/20' => $riwayat->status_sop === 'KADALUARSA',
                        ])>
                            {{-- Header --}}
                            <div class="flex items-center justify-between mb-2">
                                <span @class([
                                    'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
                                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' => $riwayat->status_sop === 'AKTIF',
                                    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' => $riwayat->status_sop === 'KADALUARSA',
                                ])>
                                    {{ $riwayat->status_sop }}
                                </span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $riwayat->created_at->translatedFormat('d M Y, H:i') }}
                                </span>
                            </div>

                            {{-- User Info --}}
                            <div class="flex items-center gap-2 mb-2">
                                <x-heroicon-o-user-circle class="w-4 h-4 text-gray-400" />
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    {{-- Jika id_user null, berarti Sistem --}}
                                    {{ $riwayat->user?->nama_lengkap ?? 'Sistem Otomatis' }}
                                </span>
                            </div>

                            {{-- Catatan --}}
                            @if($riwayat->catatan)
                                <div class="mt-3 p-3 bg-gray-50 dark:bg-gray-900 rounded-md">
                                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Catatan:</p>
                                    <p class="text-sm text-gray-700 dark:text-gray-300">
                                        {{ $riwayat->catatan }}
                                    </p>
                                </div>
                            @endif

                            {{-- Dokumen Snapshot --}}
                            @if($riwayat->dokumen_path)
                                <div class="mt-3">
                                    <a href="{{ asset('storage/' . $riwayat->dokumen_path) }}"
                                       target="_blank"
                                       class="inline-flex items-center gap-1 text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400">
                                        <x-heroicon-o-document-arrow-down class="w-4 h-4" />
                                        Lihat Arsip File
                                    </a>
                                </div>
                            @endif

                            {{-- Timestamp Detail --}}
                            @if($riwayat->updated_at && $riwayat->updated_at->ne($riwayat->created_at))
                                <div class="mt-2 text-xs text-gray-400">
                                    Diperbarui: {{ $riwayat->updated_at->translatedFormat('d M Y, H:i') }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
