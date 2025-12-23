<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Ulang Kata Sandi - SIM SOP</title>

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
                <h2 class="text-2xl font-bold text-gray-800 font-display">Kata Sandi Baru 🔑</h2>
                <p class="text-gray-500 text-sm mt-1">Mohon buat kata sandi baru untuk akun Anda.</p>
            </div>

            <form action="{{ route('password.update') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="space-y-1 input-group">
                    <label class="block text-sm font-semibold text-gray-700 ml-1">Alamat Email</label>
                    <div class="relative">
                         <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" /></svg>
                        </div>
                        <input type="email" name="email" value="{{ $email ?? old('email') }}" readonly
                            class="block w-full pl-12 pr-4 py-3.5 bg-gray-50/80 border border-gray-200 rounded-xl text-gray-500 cursor-not-allowed font-medium"
                            placeholder="Alamat Email">
                    </div>
                </div>

                <div class="space-y-1 input-group transition-colors duration-300">
                    <label for="password" class="block text-sm font-semibold text-gray-700 ml-1">Kata Sandi Baru</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        </div>
                        <input type="password" name="password" id="password" required
                            class="block w-full pl-12 pr-12 py-3.5 bg-white/50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white transition-all duration-300 font-medium tracking-wide"
                            placeholder="Minimal 8 karakter">
                        <button type="button" onclick="togglePassword('password', 'eye-icon-pass')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg id="eye-icon-pass" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Real-time Password Validation UI -->
                <div id="password-validation" class="hidden mt-3 p-4 bg-gray-50/80 rounded-xl border border-gray-100">
                    <div class="mb-3">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs font-semibold text-gray-500">Kekuatan Kata Sandi</span>
                            <span id="strength-text" class="text-xs font-bold text-gray-400">Belum diisi</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-1.5">
                            <div id="strength-bar" class="h-1.5 rounded-full transition-all duration-500 w-0 bg-gray-300"></div>
                        </div>
                    </div>
                    
                    <ul class="space-y-2">
                        <li id="rule-length" class="flex items-center text-xs text-gray-500 transition-colors duration-300">
                            <div class="w-4 h-4 mr-2 rounded-full border-2 border-gray-300 flex items-center justify-center transition-colors duration-300 status-icon">
                                <svg class="w-2.5 h-2.5 text-white hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            </div>
                            Minimal 8 karakter
                        </li>
                        <li id="rule-mixed" class="flex items-center text-xs text-gray-500 transition-colors duration-300">
                            <div class="w-4 h-4 mr-2 rounded-full border-2 border-gray-300 flex items-center justify-center transition-colors duration-300 status-icon">
                                <svg class="w-2.5 h-2.5 text-white hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            </div>
                            Huruf besar & kecil
                        </li>
                        <li id="rule-number" class="flex items-center text-xs text-gray-500 transition-colors duration-300">
                            <div class="w-4 h-4 mr-2 rounded-full border-2 border-gray-300 flex items-center justify-center transition-colors duration-300 status-icon">
                                <svg class="w-2.5 h-2.5 text-white hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            </div>
                            Minimal 1 angka
                        </li>
                        <li id="rule-symbol" class="flex items-center text-xs text-gray-500 transition-colors duration-300">
                            <div class="w-4 h-4 mr-2 rounded-full border-2 border-gray-300 flex items-center justify-center transition-colors duration-300 status-icon">
                                <svg class="w-2.5 h-2.5 text-white hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            </div>
                            Minimal 1 simbol/tanda baca
                        </li>

                    </ul>
                </div>

                <div class="space-y-1 input-group transition-colors duration-300">
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 ml-1">Konfirmasi Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                             <svg class="h-5 w-5 text-gray-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="block w-full pl-12 pr-12 py-3.5 bg-white/50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white transition-all duration-300 font-medium tracking-wide"
                            placeholder="Konfirmasi kata sandi baru">
                        <button type="button" onclick="togglePassword('password_confirmation', 'eye-icon-conf')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg id="eye-icon-conf" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <p id="match-status" class="text-xs font-semibold mt-1 hidden"></p>
                </div>

                <button type="submit"
                    class="group relative w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-brand-500/30 text-sm font-bold text-white bg-gradient-to-r from-brand-600 to-brand-500 hover:from-brand-500 hover:to-brand-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition-all duration-300 transform hover:-translate-y-0.5">
                    Simpan Kata Sandi Baru
                </button>
            </form>
        </div>
    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const passwordConfirmationInput = document.getElementById('password_confirmation');
        const validationBox = document.getElementById('password-validation');
        const strengthBar = document.getElementById('strength-bar');
        const strengthText = document.getElementById('strength-text');

        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
            } else {
                input.type = 'password';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
            }
        }
        
        const rules = {
            length: { regex: /.{8,}/, element: document.getElementById('rule-length') },
            mixed: { regex: /^(?=.*[a-z])(?=.*[A-Z])/, element: document.getElementById('rule-mixed') },
            number: { regex: /[0-9]/, element: document.getElementById('rule-number') },
            symbol: { regex: /[^A-Za-z0-9]/, element: document.getElementById('rule-symbol') } 
        };
        
        const matchStatus = document.getElementById('match-status');

        function updateStatus(element, isValid) {
            const iconContainer = element.querySelector('.status-icon');
            const checkIcon = element.querySelector('svg');

            if (isValid) {
                element.classList.remove('text-gray-500');
                element.classList.add('text-brand-600', 'font-medium'); // Green text
                
                iconContainer.classList.remove('border-gray-300');
                iconContainer.classList.add('bg-brand-500', 'border-brand-500'); // Green circle
                checkIcon.classList.remove('hidden');
            } else {
                element.classList.add('text-gray-500');
                element.classList.remove('text-brand-600', 'font-medium');
                
                iconContainer.classList.add('border-gray-300');
                iconContainer.classList.remove('bg-brand-500', 'border-brand-500');
                checkIcon.classList.add('hidden');
            }
        }

        function validatePassword() {
            const val = passwordInput.value;
            const confirmVal = passwordConfirmationInput.value;
            
            // Show validation box when typing starts in either field
            if (val.length > 0) {
                validationBox.classList.remove('hidden');
            } else {
                validationBox.classList.add('hidden');
            }

            let validCount = 0;
            
            // Check standard rules
            for (const key in rules) {
                const rule = rules[key];
                const isValid = rule.regex.test(val);
                updateStatus(rule.element, isValid);
                if (isValid) validCount++;
            }

            // Check Match Logic (Red/Green below field)
            if (confirmVal.length > 0) {
                matchStatus.classList.remove('hidden');
                if (val === confirmVal) {
                    matchStatus.textContent = 'Kata sandi cocok';
                    matchStatus.className = 'text-xs font-semibold mt-1 text-brand-600';
                } else {
                    matchStatus.textContent = 'Kata sandi tidak cocok';
                    matchStatus.className = 'text-xs font-semibold mt-1 text-red-500';
                }
            } else {
                matchStatus.classList.add('hidden');
            }

            // Strength Meter Logic
            let strength = 0;
            if (validCount === 1) strength = 25;
            else if (validCount === 2) strength = 50;
            else if (validCount === 3) strength = 75;
            else if (validCount === 4) strength = 100;

            strengthBar.style.width = strength + '%';

            if (validCount < 2) {
                strengthBar.className = 'h-1.5 rounded-full transition-all duration-500 bg-red-500';
                strengthText.textContent = 'Lemah';
                strengthText.className = 'text-xs font-bold text-red-500';
            } else if (validCount < 4) {
                strengthBar.className = 'h-1.5 rounded-full transition-all duration-500 bg-yellow-500';
                strengthText.textContent = 'Sedang';
                strengthText.className = 'text-xs font-bold text-yellow-500';
            } else {
                strengthBar.className = 'h-1.5 rounded-full transition-all duration-500 bg-brand-500';
                strengthText.textContent = 'Kuat';
                strengthText.className = 'text-xs font-bold text-brand-600';
            }
            
            if (val.length === 0) strengthBar.style.width = '0%';
        }

        passwordInput.addEventListener('input', validatePassword);
        passwordConfirmationInput.addEventListener('input', validatePassword);
    </script>
</body>
</html>
