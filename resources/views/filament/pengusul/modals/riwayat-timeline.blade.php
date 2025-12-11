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
                    'bg-gray-100 text-gray-800' => $record->status === 'DRAFT',
                    'bg-yellow-100 text-yellow-800' => $record->status === 'DALAM REVIEW',
                    'bg-red-100 text-red-800' => $record->status === 'REVISI',
                    'bg-green-100 text-green-800' => $record->status === 'AKTIF',
                    'bg-gray-100 text-gray-600' => in_array($record->status, ['KADALUARSA', 'ARCHIVED']),
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
                            'absolute -left-9 flex items-center justify-center w-6 h-6 rounded-full ring-4 ring-white dark:ring-gray-900',
                            'bg-gray-400' => $riwayat->status_sop === 'DRAFT',
                            'bg-yellow-500' => $riwayat->status_sop === 'DALAM REVIEW',
                            'bg-red-500' => $riwayat->status_sop === 'REVISI',
                            'bg-green-500' => $riwayat->status_sop === 'AKTIF',
                            'bg-gray-500' => in_array($riwayat->status_sop, ['KADALUARSA', 'ARCHIVED']),
                        ])>
                            @if($index === 0)
                                <x-heroicon-s-check class="w-3 h-3 text-black" />
                            @else
                                <span class="w-2 h-2 bg-white rounded-full"></span>
                            @endif
                        </span>

                        {{-- Content Card --}}
                        <div @class([
                            'p-4 rounded-lg border',
                            'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700',
                            'ring-2 ring-primary-500' => $index === 0,
                        ])>
                            {{-- Header --}}
                            <div class="flex items-center justify-between mb-2">
                                <span @class([
                                    'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
                                    'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' => $riwayat->status_sop === 'DRAFT',
                                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' => $riwayat->status_sop === 'DALAM REVIEW',
                                    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' => $riwayat->status_sop === 'REVISI',
                                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' => $riwayat->status_sop === 'AKTIF',
                                    'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' => in_array($riwayat->status_sop, ['KADALUARSA', 'ARCHIVED']),
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
                                <span class="text-sm text-gray-700 dark:text-gray-300">
                                    {{ $riwayat->user?->nama_lengkap ?? 'User tidak ditemukan' }}
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
                                        Lihat Dokumen Snapshot
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
