<div>
    <h1 class="font-display text-4xl font-bold mb-6 text-gray-900 dark:text-white">Panduan Role Hukum / Verifikator</h1>
    <p class="lead text-xl text-gray-600 dark:text-gray-400 mb-12">
        Verifikator bertugas memeriksa, memvalidasi, dan mengelola dokumen SOP yang diajukan oleh unit kerja sebelum disahkan.
    </p>

    <div class="space-y-16">

        <!-- 1. Login -->
        <section id="login" class="relative pl-10 border-l-2 border-gray-200 dark:border-white/10 scroll-mt-28">
            <span class="absolute -left-[1.3rem] top-0 flex items-center justify-center w-10 h-10 bg-brand-50 dark:bg-brand-900/50 rounded-full border-4 border-white dark:border-dark-900 text-brand-600 font-bold">1</span>
            
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Login Hukum / Verifikator</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Pengguna dengan role Hukum/Verifikator melakukan login menggunakan username dan password yang telah diberikan oleh sistem.
            </p>
            <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-md">
                <img src="{{ asset('img/guide/verifikator/login-verifikator.png') }}" alt="Login Verifikator" class="w-full">
            </div>
        </section>

        <!-- 2. Dashboard -->
        <section id="dashboard" class="relative pl-10 border-l-2 border-gray-200 dark:border-white/10 scroll-mt-28">
            <span class="absolute -left-[1.3rem] top-0 flex items-center justify-center w-10 h-10 bg-brand-50 dark:bg-brand-900/50 rounded-full border-4 border-white dark:border-dark-900 text-brand-600 font-bold">2</span>

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Dashboard Verifikator</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-4">
                Pada halaman Dashboard, sistem menampilkan ringkasan informasi penting:
            </p>
            <ul class="list-disc pl-5 space-y-2 text-gray-600 dark:text-gray-400 mb-6">
                <li><strong>Total Dokumen</strong>: Jumlah keseluruhan SOP.</li>
                <li><strong>Review Tahunan</strong>: Dokumen yang mendekati masa review.</li>
                <li><strong>Akan Kedaluwarsa</strong>: Dokumen yang segera expired.</li>
                <li><strong>SOP Aktif</strong>: Jumlah dokumen yang valid dan berlaku.</li>
            </ul>
            <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-md">
                <img src="{{ asset('img/guide/verifikator/dashboard-verifikator.png') }}" alt="Dashboard Verifikator" class="w-full">
            </div>
        </section>

        <!-- 3. Menu SOP Aktif -->
        <section id="sop-aktif" class="relative pl-10 border-l-2 border-gray-200 dark:border-white/10 scroll-mt-28">
            <span class="absolute -left-[1.3rem] top-0 flex items-center justify-center w-10 h-10 bg-brand-50 dark:bg-brand-900/50 rounded-full border-4 border-white dark:border-dark-900 text-brand-600 font-bold">3</span>

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Menu SOP Aktif</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Menampilkan seluruh SOP aktif, termasuk SOP lintas unit. Informasi meliputi Judul, Unit, Kategori, serta Tanggal Berlaku dan Kedaluwarsa.
            </p>
            <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-md mb-8">
                <img src="{{ asset('img/guide/verifikator/menu-sopaktif.png') }}" alt="Menu SOP Aktif" class="w-full">
            </div>
            
            <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-6 border border-gray-200 dark:border-white/10">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Pencarian & Filter</h3>
                <div class="grid md:grid-cols-2 gap-6 items-center">
                    <p class="text-gray-600 dark:text-gray-400 text-sm">
                        Anda dapat mencari dokumen berdasarkan judul dan memfilter list berdasarkan <strong>Unit Pemilik</strong> atau <strong>Kategori SOP</strong>.
                    </p>
                    <div class="rounded-lg overflow-hidden border border-gray-200 dark:border-white/10 shadow-sm">
                        <img src="{{ asset('img/guide/verifikator/search-filtersopaktif.png') }}" alt="Filter SOP Aktif" class="w-full">
                    </div>
                </div>
            </div>
        </section>

        <!-- 4. Menu Verifikasi SOP -->
        <section id="verifikasi-sop" class="relative pl-10 border-l-2 border-gray-200 dark:border-white/10 scroll-mt-28">
            <span class="absolute -left-[1.3rem] top-0 flex items-center justify-center w-10 h-10 bg-brand-50 dark:bg-brand-900/50 rounded-full border-4 border-white dark:border-dark-900 text-brand-600 font-bold">4</span>

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Menu Verifikasi SOP</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Pusat pengelolaan dokumen utama. Di sini Verifikator dapat Menambah, Mengedit, Menghapus, dan Memvalidasi dokumen.
            </p>
            <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-md mb-8">
                <img src="{{ asset('img/guide/verifikator/menu-verifikasisop.png') }}" alt="Menu Verifikasi SOP" class="w-full">
            </div>

            <div class="space-y-8">
                <!-- 4.1 Tambah -->
                <div class="border-t border-gray-200 dark:border-white/10 pt-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">4.1 Tambah SOP Baru</h3>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                             <p>Isi formulir pengajuan SOP:</p>
                             <ul class="list-disc pl-5">
                                 <li><strong>Unit & Judul</strong>: Tentukan pemilik dan judul dokumen.</li>
                                 <li><strong>Kategori</strong>: Internal atau Antar Unit.</li>
                                 <li><strong>Validitas</strong>: Tanggal Review (+1 tahun) dan Kedaluwarsa (+3 tahun) terisi otomatis dari Tanggal Berlaku.</li>
                                 <li><strong>Upload</strong>: File PDF maksimal 1024 KB.</li>
                             </ul>
                        </div>
                        <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-md">
                            <img src="{{ asset('img/guide/verifikator/tambah-sop.png') }}" alt="Form Tambah SOP" class="w-full">
                        </div>
                    </div>
                </div>

                <!-- 4.2 Edit & Hapus -->
                <div class="border-t border-gray-200 dark:border-white/10 pt-6 grid md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-bold text-gray-900 dark:text-white mb-2">Edit Dokumen</h4>
                        <img src="{{ asset('img/guide/verifikator/edit-sop.png') }}" alt="Edit SOP" class="w-full rounded-lg border border-gray-200 dark:border-white/10 mb-2">
                        <p class="text-xs text-gray-500">Klik tombol Edit pada menu aksi.</p>
                    </div>
                     <div>
                        <h4 class="font-bold text-gray-900 dark:text-white mb-2">Hapus Dokumen</h4>
                        <img src="{{ asset('img/guide/verifikator/hapus-sop.png') }}" alt="Hapus SOP" class="w-full rounded-lg border border-gray-200 dark:border-white/10 mb-2">
                         <p class="text-xs text-gray-500">Hapus (Soft Delete) melalui menu aksi.</p>
                    </div>
                </div>

                 <!-- 4.5 Detail -->
                 <div class="border-t border-gray-200 dark:border-white/10 pt-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">4.5 Detail & Unduh SOP</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Pilih <strong>Detail</strong> untuk melihat pratinjau dokumen langsung di aplikasi, atau <strong>Unduh</strong> untuk menyimpan file PDF.
                    </p>
                    <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-md">
                        <img src="{{ asset('img/guide/verifikator/detail-previewsop.png') }}" alt="Detail SOP" class="w-full">
                    </div>
                </div>
            </div>
        </section>

        <!-- 5. Menu Riwayat SOP -->
        <section id="riwayat-sop" class="relative pl-10 border-l-2 border-gray-200 dark:border-white/10 scroll-mt-28">
            <span class="absolute -left-[1.3rem] top-0 flex items-center justify-center w-10 h-10 bg-brand-50 dark:bg-brand-900/50 rounded-full border-4 border-white dark:border-dark-900 text-brand-600 font-bold">5</span>

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Menu Riwayat SOP</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Menampilkan histori perubahan dokumen, termasuk log aktivitas, siapa yang mengubah, dan arsip file versi lama.
            </p>
            <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-md mb-8">
                <img src="{{ asset('img/guide/verifikator/menu-riwayatsop.png') }}" alt="Daftar Riwayat" class="w-full">
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                 <div>
                    <h3 class="font-bold text-gray-900 dark:text-white mb-3">Pencarian Riwayat</h3>
                    <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-md">
                        <img src="{{ asset('img/guide/verifikator/search-filtersopriwayat.png') }}" alt="Filter Riwayat" class="w-full">
                    </div>
                 </div>
                 <div>
                    <h3 class="font-bold text-gray-900 dark:text-white mb-3">Detail & Log Perubahan</h3>
                    <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-md">
                        <img src="{{ asset('img/guide/verifikator/detail-riwayatsop.png') }}" alt="Detail Riwayat" class="w-full">
                    </div>
                 </div>
            </div>
        </section>

        <!-- 6. Menu Sampah -->
        <section id="sampah" class="relative pl-10 border-l-2 border-gray-200 dark:border-white/10 scroll-mt-28">
            <span class="absolute -left-[1.3rem] top-0 flex items-center justify-center w-10 h-10 bg-brand-50 dark:bg-brand-900/50 rounded-full border-4 border-white dark:border-dark-900 text-brand-600 font-bold">6</span>

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Menu Sampah</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Dokumen yang dihapus (Soft Delete) akan masuk ke sini. Verifikator dapat memulihkan dokumen jika diperlukan.
            </p>
            <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-md">
                <img src="{{ asset('img/guide/verifikator/menu-sampah.png') }}" alt="Menu Sampah" class="w-full">
            </div>
        </section>

    </div>
</div>
