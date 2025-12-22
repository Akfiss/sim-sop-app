<div>
    <h1 class="font-display text-4xl font-bold mb-6 text-gray-900 dark:text-white">Panduan Role Administrator</h1>
    <p class="lead text-xl text-gray-600 dark:text-gray-400 mb-12">
        Administrator memiliki hak akses penuh untuk mengelola master data (Direktorat, Unit Kerja, User) dan memantau seluruh aktivitas sistem.
    </p>

    <div class="space-y-16">
        
        <!-- 1. Login -->
        <section id="login-admin" class="relative pl-10 border-l-2 border-gray-200 dark:border-white/10 scroll-mt-28">
            <span class="absolute -left-[1.3rem] top-0 flex items-center justify-center w-10 h-10 bg-brand-50 dark:bg-brand-900/50 rounded-full border-4 border-white dark:border-dark-900 text-brand-600 font-bold">1</span>
            
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Login Administrator</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Administrator melakukan login melalui halaman masuk utama menggunakan username dan password yang telah diberikan oleh tim IT.
            </p>
            <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-md">
                <img src="{{ asset('img/guide/admin/login-admin.png') }}" alt="Halaman Login" class="w-full">
            </div>
        </section>

        <!-- 2. Dashboard -->
        <section id="dashboard" class="relative pl-10 border-l-2 border-gray-200 dark:border-white/10 scroll-mt-28">
             <span class="absolute -left-[1.3rem] top-0 flex items-center justify-center w-10 h-10 bg-brand-50 dark:bg-brand-900/50 rounded-full border-4 border-white dark:border-dark-900 text-brand-600 font-bold">2</span>

             <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Dashboard Administrator</h2>
             <p class="text-gray-600 dark:text-gray-400 mb-4">
                Pada halaman Dashboard, sistem menampilkan ringkasan statistik penting:
             </p>
             <ul class="list-disc pl-5 space-y-2 text-gray-600 dark:text-gray-400 mb-6">
                 <li><strong>Statistik User</strong>: Jumlah pengguna aktif dan nonaktif.</li>
                 <li><strong>Total Data</strong>: Jumlah Direktorat dan Unit Kerja terdaftar.</li>
                 <li><strong>Ringkasan SOP</strong>: Grafik atau angka dokumen SOP berdasarkan status.</li>
             </ul>
             <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-md">
                <img src="{{ asset('img/guide/admin/dashboard-admin.png') }}" alt="Dashboard Admin" class="w-full">
            </div>
        </section>

        <!-- 3. Menu Direktorat -->
        <section id="data-direktorat" class="relative pl-10 border-l-2 border-gray-200 dark:border-white/10 scroll-mt-28">
            <span class="absolute -left-[1.3rem] top-0 flex items-center justify-center w-10 h-10 bg-brand-50 dark:bg-brand-900/50 rounded-full border-4 border-white dark:border-dark-900 text-brand-600 font-bold">3</span>

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Menu Data Direktorat</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Menu ini digunakan untuk mengelola struktur organisasi tingkat atas. Daftar menampilkan Kode dan Nama Direktorat.
            </p>
            <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-md mb-8">
                <img src="{{ asset('img/guide/admin/menu-direktorat.png') }}" alt="Menu Direktorat" class="w-full">
            </div>

            <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-6 border border-gray-200 dark:border-white/10 space-y-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Aksi yang Tersedia</h3>
                
                <div class="flex gap-4">
                     <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 text-sm font-bold">A</div>
                     <div>
                         <h4 class="font-medium text-gray-900 dark:text-white">Tambah Direktorat</h4>
                         <p class="text-sm text-gray-600 dark:text-gray-400">Klik tombol Tambah. Nama direktorat tidak boleh duplikat. Kode digenerate otomatis.</p>
                     </div>
                </div>

                <div class="flex gap-4">
                     <div class="flex-shrink-0 w-8 h-8 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center text-amber-600 text-sm font-bold">B</div>
                     <div>
                         <h4 class="font-medium text-gray-900 dark:text-white">Edit & Hapus</h4>
                         <p class="text-sm text-gray-600 dark:text-gray-400">Klik ikon titik tiga pada baris data untuk Mengedit atau Menghapus data.</p>
                     </div>
                </div>
            </div>
        </section>

        <!-- 4. Menu Unit Kerja -->
        <section id="data-unit-kerja" class="relative pl-10 border-l-2 border-gray-200 dark:border-white/10 scroll-mt-28">
            <span class="absolute -left-[1.3rem] top-0 flex items-center justify-center w-10 h-10 bg-brand-50 dark:bg-brand-900/50 rounded-full border-4 border-white dark:border-dark-900 text-brand-600 font-bold">4</span>

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Menu Data Unit Kerja</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Unit Kerja adalah entitas yang akan menjadi pemilik (owner) dari dokumen SOP. Setiap unit harus terhubung dengan satu Direktorat.
            </p>
            
             <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-md mb-8">
                <img src="{{ asset('img/guide/admin/menu-unitkerja.png') }}" alt="Daftar Unit Kerja" class="w-full">
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                 <div class="space-y-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Form Penambahan</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Isi Nama Unit dan pilih Direktorat Induk.</p>
                     <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-md">
                        <img src="{{ asset('img/guide/admin/tambah-unit.png') }}" alt="Form Tambah Unit" class="w-full">
                    </div>
                 </div>
                 <div class="space-y-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Filter Data</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Gunakan filter untuk menampilkan unit berdasarkan direktorat tertentu.</p>
                    <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-md">
                         <img src="{{ asset('img/guide/admin/filter-unit.png') }}" alt="Filter Unit" class="w-full">
                    </div>
                 </div>
            </div>
        </section>

        <!-- 5. Menu User -->
        <section id="manajemen-user" class="relative pl-10 border-l-2 border-gray-200 dark:border-white/10 scroll-mt-28">
            <span class="absolute -left-[1.3rem] top-0 flex items-center justify-center w-10 h-10 bg-brand-50 dark:bg-brand-900/50 rounded-full border-4 border-white dark:border-dark-900 text-brand-600 font-bold">5</span>

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Manajemen User</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Pusat pengelolaan akun pengguna. Administrator dapat memfilter user berdasarkan Role (Admin, Pengusul, Verifikator, dll) atau Status (Aktif/Nonaktif).
            </p>
            
            <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-md mb-8">
                <img src="{{ asset('img/guide/admin/menu-user.png') }}" alt="Daftar User" class="w-full">
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3">Tambah Akun Baru</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Isi form lengkap mulai dari Username, Email, Password, hingga Role.
                        <br><span class="text-xs text-amber-600">*Unit Kerja wajib diisi untuk role Pengusul.</span>
                    </p>
                    <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-md">
                         <img src="{{ asset('img/guide/admin/tambah-user.png') }}" alt="Tambah User" class="w-full">
                    </div>
                </div>
                <div>
                     <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3">Filter & Pencarian</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Gunakan toggle kolom untuk menyembunyikan informasi yang tidak perlu, atau filter role spesifik.
                    </p>
                    <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-md">
                         <img src="{{ asset('img/guide/admin/search-filteruser.png') }}" alt="Filter User" class="w-full">
                    </div>
                </div>
            </div>
        </section>

        <!-- 6. Dokumen SOP -->
        <section id="dokumen-sop" class="relative pl-10 border-l-2 border-gray-200 dark:border-white/10 scroll-mt-28">
            <span class="absolute -left-[1.3rem] top-0 flex items-center justify-center w-10 h-10 bg-brand-50 dark:bg-brand-900/50 rounded-full border-4 border-white dark:border-dark-900 text-brand-600 font-bold">6</span>

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Semua Dokumen SOP</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Monitoring seluruh dokumen yang ada di sistem. Tabel ini menampilkan Judul, Unit, Status, dan Tanggal Pengesahan.
            </p>
            <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-md mb-6">
                <img src="{{ asset('img/guide/admin/menu-sop.png') }}" alt="Daftar SOP" class="w-full">
            </div>
            <div class="bg-amber-50 dark:bg-amber-900/20 p-4 rounded-lg border-l-4 border-amber-500">
                <p class="text-sm text-amber-800 dark:text-amber-300 m-0">
                    <strong>Catatan:</strong> Menghapus SOP dari menu ini akan memindahkannya ke "Sampah" (Soft Delete). Data masih bisa dipulihkan.
                </p>
            </div>
        </section>

        <!-- 7. Sampah -->
        <section id="sampah" class="relative pl-10 border-l-2 border-gray-200 dark:border-white/10 scroll-mt-28">
            <span class="absolute -left-[1.3rem] top-0 flex items-center justify-center w-10 h-10 bg-brand-50 dark:bg-brand-900/50 rounded-full border-4 border-white dark:border-dark-900 text-brand-600 font-bold">7</span>

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Menu Sampah</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Tempat penyimpanan sementara dokumen yang dihapus. Administrator memiliki opsi untuk <strong>Memulihkan (Restore)</strong> atau <strong>Menghapus Permanen</strong>.
            </p>
            <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-md">
                <img src="{{ asset('img/guide/admin/menu-sampah.png') }}" alt="Menu Sampah" class="w-full">
            </div>
        </section>
    </div>
</div>
