<div>
    <h1 class="font-display text-4xl font-bold mb-6 text-gray-900 dark:text-white">Panduan Role Direksi</h1>
    <p class="lead text-xl text-gray-600 dark:text-gray-400 mb-12">
        Direksi memiliki akses untuk memantau kinerja seluruh unit kerja dan melihat status dokumen SOP di bawah direktoratnya.
    </p>

    <div class="space-y-16">

        <!-- 1. Login -->
        <section id="login" class="relative pl-10 border-l-2 border-gray-200 dark:border-white/10 scroll-mt-28">
            <span class="absolute -left-[1.3rem] top-0 flex items-center justify-center w-10 h-10 bg-brand-50 dark:bg-brand-900/50 rounded-full border-4 border-white dark:border-dark-900 text-brand-600 font-bold">1</span>
            
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Login Direksi</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Pengguna dengan role Direksi melakukan login menggunakan username dan password yang telah diberikan oleh sistem.
            </p>
            <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-md">
                <img src="{{ asset('img/guide/direksi/login-direksi.png') }}" alt="Login Direksi" class="w-full">
            </div>
        </section>

        <!-- 2. Dashboard -->
        <section id="dashboard" class="relative pl-10 border-l-2 border-gray-200 dark:border-white/10 scroll-mt-28">
            <span class="absolute -left-[1.3rem] top-0 flex items-center justify-center w-10 h-10 bg-brand-50 dark:bg-brand-900/50 rounded-full border-4 border-white dark:border-dark-900 text-brand-600 font-bold">2</span>

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Dashboard Direksi</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-4">
                Dashboard menyajikan ringkasan eksekutif dan visualisasi data:
            </p>
            <ul class="list-disc pl-5 space-y-2 text-gray-600 dark:text-gray-400 mb-6">
                <li><strong>Statistik Utama</strong>: Total SOP, SOP Aktif, dan Jumlah Unit Kerja di bawah direktorat.</li>
                <li><strong>Grafik Distribusi</strong>: Visualisasi status SOP (Draft, Revisi, Aktif, dll).</li>
                <li><strong>Kinerja Unit</strong>: Grafik batang jumlah SOP per unit kerja.</li>
            </ul>
            <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-md">
                <img src="{{ asset('img/guide/direksi/dashboard-direksi.png') }}" alt="Dashboard Direksi" class="w-full">
            </div>
        </section>

        <!-- 3. Monitoring SOP -->
        <section id="monitoring-sop" class="relative pl-10 border-l-2 border-gray-200 dark:border-white/10 scroll-mt-28">
            <span class="absolute -left-[1.3rem] top-0 flex items-center justify-center w-10 h-10 bg-brand-50 dark:bg-brand-900/50 rounded-full border-4 border-white dark:border-dark-900 text-brand-600 font-bold">3</span>

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Menu Monitoring SOP</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Menampilkan seluruh dokumen SOP (Aktif & Kedaluwarsa) dari unit di bawah direktorat maupun lintas direktorat.
            </p>
            <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-md mb-8">
                <img src="{{ asset('img/guide/direksi/menu-monitoringsop.png') }}" alt="Monitoring SOP" class="w-full">
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                <div>
                     <h3 class="font-bold text-gray-900 dark:text-white mb-3">3.1 Pencarian & Filter</h3>
                     <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Filter data berdasarkan Direktorat, Unit Kerja, Kategori, atau Status.</p>
                     <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-md">
                        <img src="{{ asset('img/guide/direksi/search-filtersop.png') }}" alt="Filter SOP" class="w-full">
                    </div>
                </div>
                 <div>
                     <h3 class="font-bold text-gray-900 dark:text-white mb-3">3.2 Detail & Unduh</h3>
                     <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Lihat pratinjau dokumen, validitas tanggal, dan unduh file PDF.</p>
                     <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-md">
                        <img src="{{ asset('img/guide/direksi/detail-sop.png') }}" alt="Detail SOP" class="w-full">
                    </div>
                </div>
            </div>
        </section>

        <!-- 4. Riwayat SOP -->
        <section id="riwayat-sop" class="relative pl-10 border-l-2 border-gray-200 dark:border-white/10 scroll-mt-28">
            <span class="absolute -left-[1.3rem] top-0 flex items-center justify-center w-10 h-10 bg-brand-50 dark:bg-brand-900/50 rounded-full border-4 border-white dark:border-dark-900 text-brand-600 font-bold">4</span>

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Menu Riwayat SOP</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Melacak histori perubahan dokumen SOP. Informasi mencakup Judul, Unit, Status Terkini, dan Waktu Pembaruan.
            </p>
            <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-md mb-8">
                <img src="{{ asset('img/guide/direksi/menu-riwayatsop.png') }}" alt="Menu Riwayat" class="w-full">
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                     <h3 class="font-bold text-gray-900 dark:text-white mb-3">Pencarian Riwayat</h3>
                     <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-md">
                        <img src="{{ asset('img/guide/direksi/search-filterriwayat.png') }}" alt="Cari Riwayat" class="w-full">
                    </div>
                </div>
                <div>
                     <h3 class="font-bold text-gray-900 dark:text-white mb-3">Detail & Log Perubahan</h3>
                     <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-md">
                        <img src="{{ asset('img/guide/direksi/detail-riwayatsop.png') }}" alt="Detail Riwayat" class="w-full">
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
