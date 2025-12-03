<div id="login-background-container" class="fixed inset-0 z-[-1] overflow-hidden bg-gray-900">
    </div>

<style>
    .bg-slide {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0;
        transition: opacity 2s ease-in-out; /* Durasi transisi fade */
        z-index: -1;
    }
    .bg-slide.active {
        opacity: 1;
        z-index: 0;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // --- KONFIGURASI GAMBAR ---
        // Masukkan daftar nama file gambar dari plugin Swis atau gambar sendiri.
        // Anda bisa cek folder: public/images/backgrounds/ atau public/vendor/...
        const images = [
            "https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1920&q=80",
            "https://images.unsplash.com/photo-1472214103451-9374bd1c798e?auto=format&fit=crop&w=1920&q=80",
            "https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?auto=format&fit=crop&w=1920&q=80",
            // Tambahkan path gambar lokal Anda di sini, contoh:
            // "{{ asset('images/backgrounds/01.jpg') }}",
        ];

        const container = document.getElementById('login-background-container');
        let currentIndex = 0;
        const intervalTime = 5000; // Ganti gambar setiap 5000ms (5 detik)

        // Fungsi inisialisasi gambar
        function initSlideshow() {
            images.forEach((src, index) => {
                const img = document.createElement('img');
                img.src = src;
                img.classList.add('bg-slide');
                if (index === 0) img.classList.add('active');
                container.appendChild(img);
            });
        }

        // Fungsi rotasi
        function changeSlide() {
            const slides = document.querySelectorAll('.bg-slide');
            slides[currentIndex].classList.remove('active');

            currentIndex = (currentIndex + 1) % slides.length;

            slides[currentIndex].classList.add('active');
        }

        if (images.length > 0) {
            initSlideshow();
            setInterval(changeSlide, intervalTime);
        }
    });
</script>
