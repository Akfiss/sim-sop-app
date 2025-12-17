<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - SIM SOP</title>

    <link rel="icon" href="{{ asset('images/faviconlogo-rs.svg') }}" type="image/svg+xml">

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        display: ['"Outfit"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#ecfdf5', 100: '#d1fae5', 200: '#a7f3d0', 300: '#6ee7b7',
                            400: '#34d399', 500: '#10b981', 600: '#059669', 700: '#047857',
                            800: '#065f46', 900: '#064e3b', 950: '#022c22',
                        },
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'blob': 'blob 7s infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        },
                         blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        .input-group:focus-within label {
            color: #059669; /* brand-600 */
        }
        .input-group:focus-within input {
            border-color: #059669; /* brand-600 */
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1); /* brand-500/10 */
        }
    </style>
</head>
<body class="relative min-h-screen flex items-center justify-center overflow-hidden font-sans text-gray-800">

    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-[url('{{ asset('images/backgrounds/hero-bg.jpg') }}')] bg-cover bg-center"></div>
        <div class="absolute inset-0 bg-gradient-to-br from-gray-900/80 via-gray-900/60 to-brand-900/40 backdrop-blur-[2px]"></div>

        <div class="absolute top-0 left-1/4 w-96 h-96 bg-brand-400/30 rounded-full mix-blend-overlay filter blur-3xl opacity-50 animate-blob"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-blue-400/30 rounded-full mix-blend-overlay filter blur-3xl opacity-50 animate-blob animation-delay-2000"></div>
    </div>

    <div class="relative z-10 w-full max-w-lg px-4">

        <div class="text-center mb-8 animate-float">
            <div class="inline-flex items-center justify-center p-3 bg-white/10 rounded-2xl backdrop-blur-md border border-white/20 shadow-2xl mb-4">
                <img src="{{ asset('images/logo-rs.png') }}" alt="Logo RS" class="h-16 w-auto object-contain">
            </div>
            <h1 class="font-display font-bold text-3xl text-white tracking-tight text-shadow-lg">SIM SOP</h1>
        </div>

        <div class="glass-card rounded-3xl p-8 md:p-10 shadow-2xl shadow-black/20">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 font-display">Password Baru 🔑</h2>
                <p class="text-gray-500 text-sm mt-1">Silakan buat password baru untuk akun Anda.</p>
            </div>

            <form action="{{ route('password.update') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="space-y-1 input-group">
                    <label class="block text-sm font-semibold text-gray-700 ml-1">Email</label>
                    <div class="relative">
                         <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" /></svg>
                        </div>
                        <input type="email" name="email" value="{{ $email ?? old('email') }}" readonly
                            class="block w-full pl-12 pr-4 py-3.5 bg-gray-50/80 border border-gray-200 rounded-xl text-gray-500 cursor-not-allowed font-medium"
                            placeholder="Email">
                    </div>
                </div>

                <div class="space-y-1 input-group transition-colors duration-300">
                    <label for="password" class="block text-sm font-semibold text-gray-700 ml-1">Password Baru</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        </div>
                        <input type="password" name="password" id="password" required
                            class="block w-full pl-12 pr-4 py-3.5 bg-white/50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white transition-all duration-300 font-medium tracking-wide"
                            placeholder="Minimal 8 karakter">
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1 input-group transition-colors duration-300">
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 ml-1">Ulangi Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                             <svg class="h-5 w-5 text-gray-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="block w-full pl-12 pr-4 py-3.5 bg-white/50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white transition-all duration-300 font-medium tracking-wide"
                            placeholder="Konfirmasi password baru">
                    </div>
                </div>

                <button type="submit"
                    class="group relative w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-brand-500/30 text-sm font-bold text-white bg-gradient-to-r from-brand-600 to-brand-500 hover:from-brand-500 hover:to-brand-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition-all duration-300 transform hover:-translate-y-0.5">
                    Simpan Password Baru
                </button>
            </form>
        </div>
    </div>

</body>
</html>
