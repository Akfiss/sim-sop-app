<div>
    <h1 class="font-display text-4xl font-bold mb-6 text-gray-900 dark:text-white">Login ke SIM-SOP</h1>
    <p class="lead text-xl text-gray-600 dark:text-gray-400 mb-8">
        Langkah awal untuk masuk dan mengakses fitur-fitur dalam aplikasi SIM-SOP.
    </p>

    <div class="space-y-12">
        <section id="akses-halaman" class="scroll-mt-28">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">1. Akses Halaman Login</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-4">
                Anda dapat mengakses halaman login dengan mengklik tombol <strong>Masuk</strong> pada pojok kanan atas halaman beranda, atau langsung mengunjungi tautan berikut:
            </p>
            <a href="{{ route('login') }}" class="inline-flex items-center text-brand-600 font-semibold hover:underline mb-6">
                {{ route('login') }}
                <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
            </a>
            
            <div class="rounded-2xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-lg relative group">
                 <div class="absolute inset-0 bg-gray-900/0 group-hover:bg-gray-900/5 transition-colors"></div>
                 <img src="{{ asset('img/guide/login-preview.png') }}" alt="Tampilan Login SIM-SOP" class="w-full">
            </div>
            <p class="text-sm text-gray-500 mt-2 text-center">Tampilan Halaman Login SIM-SOP</p>
        </section>

        <section id="masukkan-akun" class="scroll-mt-28">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">2. Masukkan Kredensial</h2>
            <div class="prose dark:prose-invert text-gray-600 dark:text-gray-400">
                <p>Pada formulir login, masukkan data berikut:</p>
                <ul class="list-disc pl-5 space-y-2">
                    <li><strong>Username</strong>: Masukkan username yang telah didaftarkan.</li>
                    <li><strong>Password</strong>: Masukkan kata sandi akun Anda.</li>
                </ul>
                <p class="mt-4">
                     Jika Anda belum memiliki akun, silakan hubungi <strong>Administrator</strong> unit kerja Anda untuk pembuatan akun baru.
                </p>
            </div>
        </section>

        <section id="lupa-password" class="scroll-mt-28">
             <div class="bg-amber-50 dark:bg-amber-900/20 border-l-4 border-amber-500 p-4 rounded-r-lg">
                <h3 class="text-lg font-bold text-amber-800 dark:text-amber-400 mb-2">Lupa Password?</h3>
                <p class="text-amber-700 dark:text-amber-300 text-sm mb-0">
                    Jika Anda lupa password, klik tautan <strong>Lupa Password?</strong> pada halaman login atau segera hubungi IT Support untuk mereset kata sandi Anda.
                </p>
            </div>
        </section>
    </div>
</div>
