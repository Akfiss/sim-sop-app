<div>
    <h1 class="font-display text-4xl font-bold mb-6 text-gray-900 dark:text-white">Panduan Akses Publik</h1>
    <p class="lead text-xl text-gray-600 dark:text-gray-400 mb-12">
        Masyarakat umum dan seluruh pegawai dapat mengakses dokumen SOP Publik tanpa perlu login. Berikut adalah panduan penggunaan fitur yang tersedia di halaman utama.
    </p>

    <div class="space-y-16">

        <!-- 1. Halaman Beranda -->
        <section id="beranda" class="relative pl-10 border-l-2 border-gray-200 dark:border-white/10 scroll-mt-28">
            <span class="absolute -left-[1.3rem] top-0 flex items-center justify-center w-10 h-10 bg-brand-50 dark:bg-brand-900/50 rounded-full border-4 border-white dark:border-dark-900 text-brand-600 font-bold">1</span>
            
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Halaman Beranda</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Saat mengakses portal, Anda akan diarahkan ke Halaman Beranda. Di sini Anda dapat melihat statistik jumlah dokumen SOP yang tersedia dan daftar dokumen terbaru.
            </p>
            <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-md">
                <img src="{{ asset('img/guide/publik/beranda.png') }}" alt="Halaman Beranda" class="w-full">
            </div>
        </section>

        <!-- 2. Pencarian & Filter -->
        <section id="pencarian" class="relative pl-10 border-l-2 border-gray-200 dark:border-white/10 scroll-mt-28">
            <span class="absolute -left-[1.3rem] top-0 flex items-center justify-center w-10 h-10 bg-brand-50 dark:bg-brand-900/50 rounded-full border-4 border-white dark:border-dark-900 text-brand-600 font-bold">2</span>

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Pencarian & Filter Dokumen</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Untuk menemukan SOP yang spesifik, gunakan fitur pencarian yang tersedia di bagian atas daftar dokumen.
            </p>
            
            <div class="grid md:grid-cols-2 gap-8">
                <div>
                     <h3 class="font-bold text-gray-900 dark:text-white mb-3">Kolom Pencarian</h3>
                     <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Ketik kata kunci <strong>Judul SOP</strong> atau <strong>Nomor SK</strong>. Sistem akan mencari secara otomatis (real-time).
                     </p>
                     <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-md">
                        <img src="{{ asset('img/guide/publik/search-sop.png') }}" alt="Pencarian SOP" class="w-full">
                    </div>
                </div>
                <div>
                     <h3 class="font-bold text-gray-900 dark:text-white mb-3">Filter Unit Kerja</h3>
                     <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Gunakan dropdown untuk menyaring dokumen berdasarkan <strong>Direktorat</strong> dan <strong>Unit Kerja</strong> spesifik.
                     </p>
                     <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-md">
                        <img src="{{ asset('img/guide/publik/filter-direktoratunit.png') }}" alt="Filter Direktorat & Unit" class="w-full">
                    </div>
                </div>
            </div>
        </section>

        <!-- 3. Membaca & Mengunduh -->
        <section id="akses-dokumen" class="relative pl-10 border-l-2 border-gray-200 dark:border-white/10 scroll-mt-28">
            <span class="absolute -left-[1.3rem] top-0 flex items-center justify-center w-10 h-10 bg-brand-50 dark:bg-brand-900/50 rounded-full border-4 border-white dark:border-dark-900 text-brand-600 font-bold">3</span>

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Membaca & Mengunduh SOP</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Setiap kartu SOP menampilkan informasi Judul, Kategori, Unit Pemilik, dan Nomor SK.
            </p>
            
            <ul class="list-disc pl-5 space-y-4 text-gray-600 dark:text-gray-400 mb-6">
                <li>
                    <strong>Buka Dokumen</strong>: Klik tombol ini untuk membuka file PDF lengkap di tab baru. Anda dapat membaca atau mengunduh file tersebut.
                </li>
                <li>
                    <strong>Ringkasan AI</strong>: Klik tombol dengan ikon <svg class="w-4 h-4 inline text-brand-500" fill="currentColor" viewBox="0 0 24 24"><path d="M19 9l1.25-2.75L23 5l-2.75-1.25L19 1l-1.25 2.75L15 5l2.75 1.25L19 9zm-7.5.5L9 4 6.5 9.5 1 12l5.5 2.5L9 20l2.5-5.5L17 12l-5.5-2.5zM19 15l-1.25 2.75L15 19l2.75 1.25L19 23l1.25-2.75L23 19l-2.75-1.25L19 15z"/></svg> untuk mendapatkan rangkuman cepat isi dokumen yang dibuat oleh kecerdasan buatan.
                </li>
            </ul>

            <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-md">
                <img src="{{ asset('img/guide/publik/membaca-unduhsop.png') }}" alt="Membaca dan Unduh SOP" class="w-full">
            </div>
        </section>

        <!-- 4. Fitur Ringkasan AI -->
        <section id="ai-summary" class="relative pl-10 border-l-2 border-gray-200 dark:border-white/10 scroll-mt-28">
            <span class="absolute -left-[1.3rem] top-0 flex items-center justify-center w-10 h-10 bg-brand-50 dark:bg-brand-900/50 rounded-full border-4 border-white dark:border-dark-900 text-brand-600 font-bold">4</span>

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Fitur Ringkasan AI</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Hemat waktu membaca dengan fitur Ringkasan AI. Sistem akan menganalisis dokumen PDF dan menyajikan poin-poin penting seperti Tujuan, Ruang Lingkup, dan Prosedur Utama dalam hitungan detik.
            </p>
            <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-md">
                <img src="{{ asset('img/guide/publik/fitur-aigemini.png') }}" alt="Fitur AI Summary" class="w-full">
            </div>
        </section>

    </div>
</div>
