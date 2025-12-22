<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIM-SOP - RSUP Prof. Ngoerah</title>

    <link rel="icon" href="{{ asset('images/faviconlogo-rs.svg') }}" type="image/svg+xml">

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
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
                        dark: {
                            900: '#0f172a', 800: '#1e293b', 700: '#334155',
                        }
                    },
                    animation: {
                        'blob': 'blob 7s infinite',
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        }
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        .dark .glass {
            background: rgba(15, 23, 42, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .dark .glass-card {
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        ::-webkit-scrollbar { width: 10px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 5px; border: 2px solid transparent; background-clip: content-box; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .dark ::-webkit-scrollbar-thumb { background: #475569; }
    </style>

    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-gray-50 text-gray-900 dark:bg-dark-900 dark:text-gray-100 transition-colors duration-500 overflow-x-hidden"
      x-data="appLogic()"
      @scroll.window="isScrolled = (window.pageYOffset > 20)">

    <script>
        function appLogic() {
            return {
                isScrolled: false,
                mobileMenuOpen: false,
                darkMode: false,
                isLoading: false,
                search: new URLSearchParams(window.location.search).get('search') || '',
                direktoratId: new URLSearchParams(window.location.search).get('direktorat_id') || '',
                unitId: new URLSearchParams(window.location.search).get('unit_id') || '',
                currentPath: window.location.pathname + window.location.search,

                init() {
                    this.darkMode = localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);

                    this.$watch('search', value => {
                        this.handleSearch();
                    });

                    window.addEventListener('popstate', () => {
                        const newPath = window.location.pathname + window.location.search;
                        if (newPath !== this.currentPath) {
                            this.currentPath = newPath;
                            this.search = new URLSearchParams(window.location.search).get('search') || '';
                            this.direktoratId = new URLSearchParams(window.location.search).get('direktorat_id') || '';
                            this.unitId = new URLSearchParams(window.location.search).get('unit_id') || '';
                            this.updateResults(window.location.href, false);
                        }
                    });
                },

                toggleTheme() {
                    this.darkMode = !this.darkMode;
                    localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
                    if (this.darkMode) document.documentElement.classList.add('dark');
                    else document.documentElement.classList.remove('dark');
                },

                scrollToTop() {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },
                scrollToSection(id) {
                    const el = document.getElementById(id);
                    if(el) el.scrollIntoView({ behavior: 'smooth' });
                },

                async updateResults(url = null, pushToHistory = true) {
                    this.isLoading = true;
                    if (!url) {
                        const params = new URLSearchParams();
                        if (this.search) params.append('search', this.search);
                        if (this.direktoratId) params.append('direktorat_id', this.direktoratId);
                        if (this.unitId) params.append('unit_id', this.unitId);
                        url = `{{ route('landing-page') }}?${params.toString()}`;
                    }

                    try {
                        const response = await fetch(url, {
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        const html = await response.text();
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newList = doc.getElementById('dokumen-list');

                        if (newList) {
                            document.getElementById('dokumen-list').innerHTML = newList.innerHTML;
                            setTimeout(() => { AOS.refreshHard(); }, 200);

                            if (pushToHistory) {
                                window.history.pushState({}, '', url);
                                this.currentPath = window.location.pathname + window.location.search;
                            }
                            
                            // Scroll adjustment logic for search results...
                             const target = document.getElementById('dokumen');
                            if(target && (this.search || this.direktoratId || this.unitId)) {
                                const offset = 80;
                                const bodyRect = document.body.getBoundingClientRect().top;
                                const elementRect = target.getBoundingClientRect().top;
                                const elementPosition = elementRect - bodyRect;
                                const offsetPosition = elementPosition - offset;

                                if (window.pageYOffset > offsetPosition) {
                                    window.scrollTo({ top: offsetPosition, behavior: 'smooth' });
                                }
                            }
                        }
                    } catch (error) {
                        console.error('Gagal memuat data:', error);
                    } finally {
                        this.isLoading = false;
                    }
                },

                handleSearch() { this.updateResults(); },
                handleFilter(id) { 
                    this.direktoratId = id; 
                    this.unitId = ''; 
                    this.updateResults(); 
                },
                handleFilterUnit(id) { this.unitId = id; this.updateResults(); },
                handleReset() {
                    this.search = '';
                    this.direktoratId = '';
                    this.unitId = '';
                    this.updateResults();
                },
                handleMainClick(e) {
                    const link = e.target.closest('#pagination-container a');
                    if (link && link.href) {
                        e.preventDefault();
                        this.updateResults(link.href);
                    }
                }
            }
        }
    </script>
    
    {{ $slot }}

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            once: true,
            offset: 50,
            duration: 800,
            easing: 'ease-out-cubic',
        });
        // 3D DNA Animation
        document.addEventListener('DOMContentLoaded', () => {
             const container = document.getElementById('dna-canvas-container');
            if (!container) return;
            const scene = new THREE.Scene();
            scene.fog = new THREE.FogExp2(0xffffff, 0.05);
            const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
            const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
            renderer.setSize(window.innerWidth, window.innerHeight);
            renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
            container.appendChild(renderer.domElement);
            const dnaGroup = new THREE.Group();
            scene.add(dnaGroup);
            const count = 40;
            const geometry = new THREE.SphereGeometry(0.2, 16, 16);
            const material1 = new THREE.MeshBasicMaterial({ color: 0x10b981 });
            const material2 = new THREE.MeshBasicMaterial({ color: 0x3b82f6 });
            const lineMaterial = new THREE.LineBasicMaterial({ color: 0x94a3b8, transparent: true, opacity: 0.3 });
            for (let i = 0; i < count; i++) {
                const t = i * 0.5;
                const x = Math.cos(t) * 2;
                const z = Math.sin(t) * 2;
                const y = i * 0.4 - (count * 0.4) / 2;
                const particle1 = new THREE.Mesh(geometry, material1);
                particle1.position.set(x, y, z);
                dnaGroup.add(particle1);
                const particle2 = new THREE.Mesh(geometry, material2);
                particle2.position.set(-x, y, -z);
                dnaGroup.add(particle2);
                const points = [];
                points.push(new THREE.Vector3(x, y, z));
                points.push(new THREE.Vector3(-x, y, -z));
                const lineGeometry = new THREE.BufferGeometry().setFromPoints(points);
                const line = new THREE.Line(lineGeometry, lineMaterial);
                dnaGroup.add(line);
            }
            camera.position.z = 12;
            camera.position.y = 0;
            camera.rotation.y = 0;
            if (window.innerWidth > 768) {
                dnaGroup.position.x = 6;
                dnaGroup.rotation.z = Math.PI / 8;
            } else {
                dnaGroup.position.y = 4;
                dnaGroup.scale.set(0.7, 0.7, 0.7);
            }
            let mouseX = 0;
            let mouseY = 0;
            let targetX = 0;
            let targetY = 0;
            const windowHalfX = window.innerWidth / 2;
            const windowHalfY = window.innerHeight / 2;
            document.addEventListener('mousemove', (event) => {
                mouseX = (event.clientX - windowHalfX) * 0.001;
                mouseY = (event.clientY - windowHalfY) * 0.001;
            });
            window.addEventListener('resize', () => {
                camera.aspect = window.innerWidth / window.innerHeight;
                camera.updateProjectionMatrix();
                renderer.setSize(window.innerWidth, window.innerHeight);
                if (window.innerWidth > 768) {
                     dnaGroup.position.x = 6;
                     dnaGroup.position.y = 0;
                     dnaGroup.scale.set(1, 1, 1);
                     dnaGroup.rotation.z = Math.PI / 8;
                } else {
                     dnaGroup.position.x = 0;
                     dnaGroup.position.y = 4;
                     dnaGroup.scale.set(0.7, 0.7, 0.7);
                     dnaGroup.rotation.z = 0;
                }
            });
            const clock = new THREE.Clock();
            function animate() {
                requestAnimationFrame(animate);
                const elapsedTime = clock.getElapsedTime();
                dnaGroup.rotation.y += 0.005;
                dnaGroup.rotation.z = Math.sin(elapsedTime * 0.5) * 0.1;
                targetX = mouseX * 2;
                targetY = mouseY * 2;
                dnaGroup.rotation.x += 0.05 * (targetY - dnaGroup.rotation.x);
                dnaGroup.rotation.y += 0.05 * (targetX - dnaGroup.rotation.y);
                renderer.render(scene, camera);
            }
            animate();
        });
    </script>
</body>
</html>
