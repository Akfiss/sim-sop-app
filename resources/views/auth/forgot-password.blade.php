<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi - SIM SOP</title>
    <link rel="icon" href="{{ asset('images/faviconlogo-rs.svg') }}" type="image/svg+xml">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'], display: ['"Outfit"', 'sans-serif'] },
                    colors: { brand: { 50: '#ecfdf5', 100: '#d1fae5', 500: '#10b981', 600: '#059669', 900: '#064e3b' } },
                    animation: { 'float': 'float 6s ease-in-out infinite', 'blob': 'blob 7s infinite' },
                    keyframes: {
                        float: { '0%, 100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-20px)' } },
                        blob: { '0%': { transform: 'translate(0px, 0px) scale(1)' }, '33%': { transform: 'translate(30px, -50px) scale(1.1)' }, '66%': { transform: 'translate(-20px, 20px) scale(0.9)' }, '100%': { transform: 'translate(0px, 0px) scale(1)' } }
                    }
                }
            }
        }
    </script>
    <style>
        .glass-card { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.5); }
    </style>
</head>
<body class="relative min-h-screen flex items-center justify-center overflow-hidden font-sans text-gray-800">
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-[url('{{ asset('images/backgrounds/hero-bg.jpg') }}')] bg-cover bg-center"></div>
        <div class="absolute inset-0 bg-gradient-to-br from-gray-900/80 via-gray-900/60 to-brand-900/40 backdrop-blur-[2px]"></div>
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-brand-400/30 rounded-full mix-blend-overlay filter blur-3xl opacity-50 animate-blob"></div>
    </div>

    <div class="relative z-10 w-full max-w-lg px-4">
        <div class="text-center mb-8 animate-float">
             <div class="inline-flex items-center justify-center p-3 bg-white/10 rounded-2xl backdrop-blur-md border border-white/20 shadow-2xl mb-4">
                <img src="{{ asset('images/logo-rs.png') }}" alt="Logo RS" class="h-16 w-auto object-contain">
            </div>
            <h1 class="font-display font-bold text-3xl text-white tracking-tight text-shadow-lg">SIM SOP</h1>
        </div>

        <div class="glass-card rounded-3xl p-8 md:p-10 shadow-2xl shadow-black/20">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800 font-display">Atur Ulang Kata Sandi 🔒</h2>
                <p class="text-gray-500 text-sm mt-1">Mohon masukkan alamat email yang terdaftar. Kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.</p>
            </div>

            @if (session('status'))
                <div class="mb-4 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm font-medium flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-1">
                    <label for="email" class="block text-sm font-semibold text-gray-700 ml-1">Alamat Email</label>
                    <div class="relative">
                         <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        </div>
                        <input type="email" name="email" id="email" required autofocus
                            class="block w-full pl-12 pr-4 py-3.5 bg-white/50 border border-gray-200 rounded-xl text-gray-900 focus:outline-none focus:bg-white focus:border-brand-600 focus:ring-4 focus:ring-brand-500/10 transition-all duration-300 font-medium"
                            placeholder="nama@email.com">
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full flex justify-center py-3.5 px-4 rounded-xl shadow-lg shadow-brand-500/30 text-sm font-bold text-white bg-gradient-to-r from-brand-600 to-brand-500 hover:from-brand-500 hover:to-brand-400 transition-all duration-300 transform hover:-translate-y-0.5">
                    Kirim Tautan Atur Ulang
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-sm font-medium text-brand-600 hover:text-brand-500 transition-colors">
                    &larr; Kembali ke Login
                </a>
            </div>
        </div>
    </div>
</body>
</html>
