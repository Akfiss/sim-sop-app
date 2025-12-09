# SIM-SOP (Sistem Informasi Manajemen SOP)
**RSUP Prof. Dr. I.G.N.G. Ngoerah**

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel)
![Filament](https://img.shields.io/badge/Filament-V3-F2C94C?style=for-the-badge&logo=filament)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php)
![Tailwind](https://img.shields.io/badge/Tailwind_CSS-CDN-38B2AC?style=for-the-badge&logo=tailwind-css)

SIM-SOP adalah platform digital berbasis web yang dirancang untuk mendigitalkan siklus hidup (lifecycle) dokumen Standar Operasional Prosedur (SOP). Sistem ini memfasilitasi proses pengajuan, verifikasi, pengesahan, pemantauan masa berlaku, hingga review tahunan dokumen SOP secara terpusat, transparan, dan *paperless*.

---

## 🌟 Fitur Utama

Sistem ini menggunakan arsitektur **Multi-Panel**, di mana setiap *role* memiliki dashboard terpisah untuk keamanan dan fokus kerja.

### 1. 🏠 Landing Page (Publik)
* **Search & Filter:** Pencarian SOP berdasarkan judul dan filter berdasarkan Direktorat secara *realtime*.
* **SOP Listing:** Menampilkan daftar SOP yang berstatus **AKTIF** saja.
* **Dark Mode:** Dukungan tema gelap/terang dengan *toggle* otomatis.
* **Responsive:** Tampilan mobile-friendly menggunakan Tailwind CSS.
* **Akses Portal:** Pintu masuk login untuk Pengusul, Verifikator, Direksi, dan Admin.

### 2. 📝 Panel Pengusul (Staff Unit)
* **Upload SOP:** Mendukung upload dokumen PDF dengan validasi.
* **Draft System:** Bisa menyimpan dokumen sebagai *Draft* sebelum dikirim ke verifikator.
* **SOP AP (Antar Profesi/Unit):** Opsi khusus untuk SOP Lintas Unit dengan fitur *toggle* "Seluruh Unit".
* **Monitoring Status:** Memantau status (Dalam Review, Revisi, Aktif, Kadaluarsa).
* **Review Tahunan:** Notifikasi dan aksi khusus (H-30) untuk memperpanjang review tahunan tanpa upload ulang jika tidak ada perubahan.
* **Perbaikan Revisi:** Fitur edit dokumen jika ditolak/dikembalikan oleh verifikator.

### 3. ✅ Panel Verifikator (Tim Mutu/Reviewer)
* **Verifikasi Dokumen:** Melihat detail dan preview PDF langsung di browser.
* **Approval System:** Menyetujui SOP (Otomatis generate Tgl Berlaku, Tgl Review, dan Tgl Expired).
* **Revision Request:** Mengembalikan SOP ke pengusul dengan status revisi.
* **Grouping Action:** Tombol aksi yang rapi dalam dropdown menu.

### 4. 📊 Panel Direksi (Pimpinan)
* **Dashboard Monitoring:** Statistik total dokumen (Aktif, Review, Expired).
* **Charts:** Grafik visual status SOP dan kepatuhan unit kerja.
* **Read-Only Access:** Akses penuh untuk melihat seluruh dokumen tanpa bisa mengubah data.

### 5. 🔐 Panel Super Admin (IT/Administrator)
* **Manajemen User:** CRUD User dengan role dan hak akses.
* **Master Data:** Manajemen Data Direktorat dan Unit Kerja.
* **Activity Log:** (Opsional) Memantau aktivitas sistem.

---

## 🛠️ Tech Stack

* **Framework:** [Laravel 12](https://laravel.com)
* **Admin Panel:** [Filament V3](https://filamentphp.com)
* **Frontend Logic:** Livewire & Alpine.js
* **Styling:** Tailwind CSS (CDN untuk Landing Page, Native untuk Panel).
* **Database:** MySQL
* **PDF Viewer:** Native Browser Support via Iframe/Object.

---

## 🚀 Instalasi & Penggunaan (Local Development)

Ikuti langkah ini untuk menjalankan projek di komputer lain:

### Prasyarat
* PHP >= 8.2
* Composer
* Node.js & NPM
* MySQL / Laragon / XAMPP

### Langkah-langkah

1.  **Clone Repository**
    ```bash
    git clone https://github.com/akfiss/sim-sop-app.git
    cd sim-sop-app
    ```

2.  **Install Dependencies**
    ```bash
    composer install
    npm install
    ```

3.  **Setup Environment**
    * Duplikat file `.env.example` menjadi `.env`.
      ```bash
      cp .env.example .env
      ```
    * Atur konfigurasi database di file `.env`:
        ```env
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=db_simsop
        DB_USERNAME=root
        DB_PASSWORD=
        ```

4.  **Generate Key & Storage Link**
    ```bash
    php artisan key:generate
    php artisan storage:link
    ```

5.  **Migrasi Database & Seeder (PENTING)**
    Jalankan perintah ini untuk membuat tabel dan mengisi data akun dummy (Admin, Pengusul, dll).
    ```bash
    php artisan migrate:fresh --seed
    ```
    *Note: Pastikan Anda memiliki class `DatabaseSeeder` yang memanggil seeder master data.*

6.  **Build Assets**
    ```bash
    npm run build
    ```

7.  **Jalankan Server**
    ```bash
    php artisan serve
    ```
    Buka browser dan akses: `http://localhost:8000`

---

## 🔑 Akun Demo (Default Seeder)

Jika Anda menggunakan seeder bawaan, berikut adalah akun untuk testing:

| Role | Username | Password |
| :--- | :--- | :--- |
| **Super Admin** | `superadmin` | `password` |
| **Pengusul** | `pengusul` | `password` |
| **Verifikator** | `verifikator` | `password` |
| **Direksi** | `direksi` | `password` |

---

## 🤝 Kontribusi

Projek ini dikembangkan oleh **Akbar Johan Firdaus** untuk keperluan operasional RSUP Prof. Dr. I.G.N.G. Ngoerah.

---

&copy; 2025 IT Installation - RSUP Prof. Dr. I.G.N.G. Ngoerah.
