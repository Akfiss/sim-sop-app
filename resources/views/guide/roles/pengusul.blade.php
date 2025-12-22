<div>
    <h1 class="font-display text-4xl font-bold mb-6 text-gray-900 dark:text-white">Panduan Role Pengusul / Kepala Unit</h1>
    <p class="lead text-xl text-gray-600 dark:text-gray-400 mb-12">
        Pengusul (Kepala Unit) bertanggung jawab untuk mengajukan, mengelola, dan memperbarui dokumen SOP di unit kerjanya.
    </p>

    <div class="space-y-16">

        <!-- 1. Login -->
        <section id="login" class="relative pl-10 border-l-2 border-gray-200 dark:border-white/10 scroll-mt-28">
            <span class="absolute -left-[1.3rem] top-0 flex items-center justify-center w-10 h-10 bg-brand-50 dark:bg-brand-900/50 rounded-full border-4 border-white dark:border-dark-900 text-brand-600 font-bold">1</span>
            
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Login Pengusul</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Login menggunakan akun Pengusul / Kepala Unit dengan kredensial yang diberikan.
            </p>
            <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-md">
                <img src="{{ asset('img/guide/pengusul/login-pengusul.png') }}" alt="Login Pengusul" class="w-full">
            </div>
        </section>

        <!-- 2. Dashboard -->
        <section id="dashboard" class="relative pl-10 border-l-2 border-gray-200 dark:border-white/10 scroll-mt-28">
            <span class="absolute -left-[1.3rem] top-0 flex items-center justify-center w-10 h-10 bg-brand-50 dark:bg-brand-900/50 rounded-full border-4 border-white dark:border-dark-900 text-brand-600 font-bold">2</span>

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Dashboard Pengusul</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-4">
                Dashboard memberikan gambaran umum tentang status dokumen unit Anda:
            </p>
            <ul class="list-disc pl-5 space-y-2 text-gray-600 dark:text-gray-400 mb-6">
                <li><strong>Total Dokumen</strong>: Milik unit sendiri dan unit terkait.</li>
                <li><strong>SOP Aktif</strong>: Dokumen yang sedang berlaku saat ini.</li>
                <li><strong>Akan Kedaluwarsa</strong>: Peringatan dini untuk persiapan revisi/perpanjangan.</li>
            </ul>
            <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-md">
                <img src="{{ asset('img/guide/pengusul/dashboard-pengusul.png') }}" alt="Dashboard Pengusul" class="w-full">
            </div>
        </section>

        <!-- 3. Semua Dokumen SOP -->
        <section id="semua-dokumen" class="relative pl-10 border-l-2 border-gray-200 dark:border-white/10 scroll-mt-28">
            <span class="absolute -left-[1.3rem] top-0 flex items-center justify-center w-10 h-10 bg-brand-50 dark:bg-brand-900/50 rounded-full border-4 border-white dark:border-dark-900 text-brand-600 font-bold">3</span>

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Menu Semua Dokumen SOP</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Daftar lengkap seluruh SOP (Aktif & Kedaluwarsa) yang dikelola oleh unit Anda.
            </p>
            <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-md mb-8">
                <img src="{{ asset('img/guide/pengusul/menu-semuadokumensop.png') }}" alt="Semua Dokumen SOP" class="w-full">
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                 <div>
                     <h3 class="font-bold text-gray-900 dark:text-white mb-3">3.1 Pencarian & Filter</h3>
                     <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Cari berdasarkan Judul. Filter berdasarkan Unit Kerja atau Status Dokumen.</p>
                     <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-md">
                        <img src="{{ asset('img/guide/pengusul/search-filtersop.png') }}" alt="Filter SOP" class="w-full">
                    </div>
                </div>
                 <div>
                     <h3 class="font-bold text-gray-900 dark:text-white mb-3">3.2 Detail & Unduh</h3>
                     <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Lihat Detail untuk pratinjau dan info validitas. Unduh PDF jika diperlukan.</p>
                     <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-md">
                        <img src="{{ asset('img/guide/pengusul/detail-previewsop.png') }}" alt="Detail SOP" class="w-full">
                    </div>
                </div>
            </div>
        </section>

        <!-- 4. Riwayat SOP -->
        <section id="riwayat-sop" class="relative pl-10 border-l-2 border-gray-200 dark:border-white/10 scroll-mt-28">
            <span class="absolute -left-[1.3rem] top-0 flex items-center justify-center w-10 h-10 bg-brand-50 dark:bg-brand-900/50 rounded-full border-4 border-white dark:border-dark-900 text-brand-600 font-bold">4</span>

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Menu Riwayat SOP</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Jejak historis perubahan dokumen unit Anda.
            </p>
            <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-md mb-8">
                <img src="{{ asset('img/guide/pengusul/menu-riwayatsop.png') }}" alt="Menu Riwayat" class="w-full">
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                     <h3 class="font-bold text-gray-900 dark:text-white mb-3">Pencarian Riwayat</h3>
                     <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-md">
                        <img src="{{ asset('img/guide/pengusul/search-sopriwayat.png') }}" alt="Cari Riwayat" class="w-full">
                    </div>
                </div>
                <div>
                     <h3 class="font-bold text-gray-900 dark:text-white mb-3">Detail & Log</h3>
                     <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-md">
                        <img src="{{ asset('img/guide/pengusul/detail-riwayatsop.png') }}" alt="Detail Riwayat" class="w-full">
                    </div>
                </div>
            </div>
        </section>

        <!-- 5. Notifikasi -->
        <section id="notifikasi" class="relative pl-10 border-l-2 border-gray-200 dark:border-white/10 scroll-mt-28">
            <span class="absolute -left-[1.3rem] top-0 flex items-center justify-center w-10 h-10 bg-brand-50 dark:bg-brand-900/50 rounded-full border-4 border-white dark:border-dark-900 text-brand-600 font-bold">5</span>

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Fitur Notifikasi</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Sistem memberikan notifikasi otomatis untuk:
            </p>
            <ul class="list-disc pl-5 space-y-2 text-gray-600 dark:text-gray-400 mb-6">
                <li>Setiap SOP baru yang diterbitkan (milik unit).</li>
                <li>Peringatan untuk SOP yang akan segera kedaluwarsa.</li>
            </ul>
            <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-md">
                <img src="{{ asset('img/guide/pengusul/notifikasi.png') }}" alt="Notifikasi" class="w-full">
            </div>
        </section>

    </div>
</div>
