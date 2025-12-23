<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - SIM SOP</title>

    <link rel="icon" href="{{ asset('images/faviconlogo-rs.svg') }}" type="image/svg+xml">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind -->
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

    <!-- Background Layers -->
    <div class="absolute inset-0 z-0">
        <!-- Main Background Image -->
        <div class="absolute inset-0 bg-[url('{{ asset('images/backgrounds/hero-bg.jpg') }}')] bg-cover bg-center"></div>
        <!-- Overlay -->
        <div class="absolute inset-0 bg-gradient-to-br from-gray-900/80 via-gray-900/60 to-brand-900/40 backdrop-blur-[2px]"></div>

        <!-- Animated Blobs -->
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-brand-400/30 rounded-full mix-blend-overlay filter blur-3xl opacity-50 animate-blob"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-blue-400/30 rounded-full mix-blend-overlay filter blur-3xl opacity-50 animate-blob animation-delay-2000"></div>
    </div>

    <!-- Login Container -->
    <div class="relative z-10 w-full max-w-lg px-4">

        <!-- Logo Section (Floating) -->
        <div class="text-center mb-8 animate-float">
            <div class="inline-flex items-center justify-center p-3 bg-white/10 rounded-2xl backdrop-blur-md border border-white/20 shadow-2xl mb-4">
                <img src="{{ asset('images/logo-rs.png') }}" alt="Logo RS" class="h-16 w-auto object-contain">
            </div>
            <h1 class="font-display font-bold text-3xl text-white tracking-tight text-shadow-lg">SIM SOP</h1>
            <p class="text-gray-300 font-light mt-1 tracking-wide">RSUP Prof. dr. I.G.N.G. Ngoerah</p>
        </div>

        <!-- Glass Card - Login Form -->
        <div class="glass-card rounded-3xl p-8 md:p-10 shadow-2xl shadow-black/20">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 font-display">Selamat Datang 👋</h2>
                <p class="text-gray-500 text-sm mt-1">Silakan masuk untuk mengakses portal.</p>
            </div>

            <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Username -->
                <div class="space-y-1 input-group transition-colors duration-300">
                    <label for="username" class="block text-sm font-semibold text-gray-700 ml-1">Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <input type="text" name="username" id="username" required autofocus
                            class="block w-full pl-12 pr-4 py-3.5 bg-white/50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white transition-all duration-300 font-medium"
                            placeholder="Masukkan Username">
                    </div>
                    @error('username')
                        <p class="text-red-500 text-xs mt-1 font-medium flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" class="w-6 h-6" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="space-y-1 input-group transition-colors duration-300">
                    <label for="password" class="block text-sm font-semibold text-gray-700 ml-1">Password</label>
                    <div class="relative">
                         <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input type="password" name="password" id="password" required
                            class="block w-full pl-12 pr-12 py-3.5 bg-white/50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white transition-all duration-300 font-medium tracking-wide"
                            placeholder="••••••••">
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg id="eye-icon" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between pt-2">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox"
                            class="h-4 w-4 text-brand-600 focus:ring-brand-500 border-gray-300 rounded cursor-pointer">
                        <label for="remember" class="ml-2 block text-sm text-gray-600 cursor-pointer select-none">Ingat saya</label>
                    </div>

                    <!-- Optional: Link forgot password if needed in future -->
                    <a href="{{ route('password.request') }}" class="text-sm font-bold text-brand-600 hover:text-brand-500 transition-colors">
                        Lupa Password?
                    </a>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="group relative w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-brand-500/30 text-sm font-bold text-white bg-gradient-to-r from-brand-600 to-brand-500 hover:from-brand-500 hover:to-brand-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition-all duration-300 transform hover:-translate-y-0.5">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-brand-200 group-hover:text-white transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    Masuk Aplikasi
                </button>
            </form>

            <!-- Footer Small Text -->
            <div class="mt-8 text-center">
                 <p class="text-xs text-gray-400">
                    &copy; {{ date('Y') }} Instalasi SIMRS RSUP Prof. I.G.N.G. Ngoerah
                </p>
            </div>
        </div>

    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
            } else {
                input.type = 'password';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
            }
        }
    </script>
</body>
</html>
