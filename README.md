# SIM-SOP (Sistem Informasi Manajemen SOP)

**RSUP Prof. Dr. I.G.N.G. Ngoerah**

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel)
![Filament](https://img.shields.io/badge/Filament-V3-F2C94C?style=for-the-badge&logo=filament)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php)
![Tailwind](https://img.shields.io/badge/Tailwind_CSS-CDN-38B2AC?style=for-the-badge&logo=tailwind-css)

SIM-SOP adalah platform digital berbasis web yang dirancang untuk mendigitalkan siklus hidup (lifecycle) dokumen Standar Operasional Prosedur (SOP). Sistem ini memfasilitasi proses pengajuan, verifikasi, pengesahan, pemantauan masa berlaku, hingga review tahunan dokumen SOP secara terpusat, transparan, dan _paperless_.

---

## 📋 Daftar Isi

-   [Fitur Utama](#-fitur-utama)
-   [Alur Kerja Sistem](#-alur-kerja-sistem)
-   [Tech Stack](#️-tech-stack)
-   [Instalasi & Penggunaan](#-instalasi--penggunaan-local-development)
-   [Konfigurasi Fitur Tambahan](#-konfigurasi-fitur-tambahan)
-   [User Default](#-user-default)
-   [Troubleshooting](#-troubleshooting)

---

## 🌟 Fitur Utama

Sistem ini menggunakan arsitektur **Multi-Panel** dengan **Unified Login System**, di mana setiap _role_ memiliki dashboard terpisah untuk keamanan dan fokus kerja, namun login dilakukan di satu halaman yang sama dengan redirect otomatis sesuai role akun.

### 1. 🏠 Landing Page (Publik)

-   **Search & Filter Realtime:** Pencarian SOP berdasarkan judul dan filter berdasarkan Direktorat/Unit Kerja secara _realtime_.
-   **SOP Listing:** Menampilkan daftar SOP yang berstatus **AKTIF** saja dengan pagination.
-   **AI Summary (Google Gemini 2.5 Flash):** Fitur ringkasan otomatis dokumen SOP menggunakan AI.
-   **Preview PDF:** Tampilan preview dokumen PDF langsung di browser.
-   **Dark Mode:** Dukungan tema gelap/terang dengan _toggle_ otomatis.
-   **Responsive:** Tampilan mobile-friendly menggunakan Tailwind CSS.
-   **Akses Login:** Tombol "Masuk" mengarah ke halaman login terpusat (`/login`) yang berlaku untuk semua role.
-   **Panduan Interaktif:** Halaman panduan penggunaan sistem untuk setiap role dengan search & filter.

### 2. � Panel Pengusul (Kepala Unit Kerja)

-   **Read-Only Access:** Akses monitoring dan melihat dokumen SOP milik unit kerja masing-masing tanpa bisa mengubah data.
-   **Monitoring SOP Unit:** Memantau SOP yang menjadi tanggung jawab unit kerja (sebagai pemilik atau unit terkait).
-   **Preview & Download:** Melihat detail dan preview PDF, serta download dokumen SOP.
-   **Status Tracking:** Memantau status dokumen (AKTIF, KADALUARSA).
-   **Riwayat & Histori:** Melihat histori perubahan dokumen SOP.
-   **Sistem Notifikasi:** Menerima notifikasi realtime saat SOP baru diterbitkan oleh Verifikator:
    -   **SOP Regular:** Notifikasi hanya ke Unit Pemilik
    -   **SOP AP:** Notifikasi ke Unit Pemilik + semua Unit Terkait
-   **Lonceng Notifikasi:** Icon bell dengan counter notifikasi yang belum dibaca di navbar.

### 3. ✅ Panel Verifikator (Tim Mutu/Reviewer)

-   **Upload & Terbitkan SOP:** Upload dokumen PDF dan menerbitkan SOP baru dengan data lengkap:
    -   Pilih Unit Pemilik SOP
    -   Pilih Jenis SOP (Regular atau AP/Antar Profesi)
    -   Untuk SOP AP: Pilih Unit Terkait (multiple) atau toggle "Seluruh Unit"
    -   Upload file PDF dengan validasi
-   **Auto-Generate Tanggal:** Sistem otomatis set tanggal saat penerbitan:
    -   Tanggal Pengesahan (fleksibel sesuai input)
    -   Tanggal Berlaku (fleksibel sesuai input)
    -   Tanggal Review Berikutnya (1 tahun dari berlaku)
    -   Tanggal Kadaluarsa (3 tahun dari berlaku)
-   **Status Otomatis AKTIF:** SOP langsung berstatus AKTIF saat diterbitkan.
-   **Edit & Update SOP:** Edit data SOP yang sudah ada dengan tracking perubahan detail.
-   **Soft Delete:** Menghapus SOP ke "Sampah" dengan kemampuan restore.
-   **Histori Riwayat:** Melihat semua perubahan dokumen SOP lengkap dengan catatan.
-   **Grouping Action:** Tombol aksi yang rapi dalam dropdown menu (View, Edit, Delete).
-   **Notifikasi Otomatis:** Sistem mengirim notifikasi ke Unit Kerja terkait saat SOP diterbitkan:
    -   **SOP Regular:** Notifikasi hanya ke Unit Pemilik
    -   **SOP AP:** Notifikasi ke Unit Pemilik + semua Unit Terkait

### 4. 📊 Panel Direksi (Pimpinan)

-   **Dashboard Monitoring:** Statistik komprehensif:
    -   Total SOP Aktif
    -   Total SOP Kadaluarsa
    -   Total Unit Kerja
    -   Total Direktorat
-   **Charts & Grafik:** Visualisasi data SOP per direktorat dan unit kerja.
-   **Widget Stats:** Widget statistik yang informatif dan realtime.
-   **Read-Only Access:** Akses penuh untuk melihat seluruh dokumen tanpa bisa mengubah data.

### 5. 🔐 Panel Super Admin (IT/Administrator)

-   **Manajemen User:** CRUD User lengkap dengan:
    -   Role Assignment (Pengusul, Verifikator, Direksi, Admin)
    -   Unit Kerja Assignment
    -   Password Management
-   **Master Data Direktorat:** Manajemen data Direktorat dengan ID custom (DIR-xxxxx).
-   **Master Data Unit Kerja:** Manajemen data Unit Kerja dengan relasi ke Direktorat (UNIT-xxxxx).
-   **User Import/Export:** (Opsional) Fitur import user dalam jumlah banyak.
-   **Activity Log:** (Opsional) Memantau aktivitas sistem.

### 6. 🔄 Fitur Notifikasi Realtime

-   **Broadcasting Event:** Menggunakan Laravel Broadcasting untuk notifikasi realtime.
-   **Livewire Component:** Komponen `LoncengNotifikasi` yang dapat digunakan di semua panel.
-   **Auto Refresh:** Notifikasi otomatis refresh tanpa reload halaman.
-   **Mark as Read:** Fitur tandai sebagai sudah dibaca.
-   **Mark All Read:** Tandai semua notifikasi sebagai sudah dibaca sekaligus.
-   **Notifikasi Modal:** Detail notifikasi dalam modal yang elegan.
-   **Link ke Dokumen:** Tombol aksi untuk langsung melihat dokumen terkait.

### 7. 🔑 Fitur Keamanan & Authentication

-   **Unified Login System:** Satu halaman login (`/login`) untuk semua role dengan redirect otomatis ke panel sesuai role akun.
-   **Multi-Panel Architecture:** Setiap role memiliki panel terpisah (Pengusul, Verifikator, Direksi, Admin) untuk keamanan dan fokus kerja.
-   **Smart Routing:** Sistem otomatis mengarahkan user ke panel yang sesuai dengan role mereka setelah login berhasil.
-   **Password Reset via Email:** Reset password dengan link yang dikirim ke Gmail melalui SMTP.
-   **Email Notification:** Template email custom dengan branding RSUP untuk reset password.
-   **Session Management:** Manajemen sesi yang aman dengan database driver.

---

## 🔄 Alur Kerja Sistem

### Alur Lengkap Dokumen SOP

```mermaid
graph TD
    A[Verifikator Upload SOP] --> B{Jenis SOP?}
    B -->|SOP Regular| C[Pilih Unit Pemilik]
    B -->|SOP AP| D[Pilih Unit Pemilik + Unit Terkait]
    C --> E[Upload PDF & Isi Data Lengkap]
    D --> E
    E --> F[Set Tanggal Berlaku/Review/Kadaluarsa]
    F --> G[Status: AKTIF Otomatis]
    G --> H[SOP Tampil di Landing Page]
    H --> I{Kirim Notifikasi}
    I -->|SOP Regular| J[Notifikasi ke Unit Pemilik]
    I -->|SOP AP| K[Notifikasi ke Unit Pemilik + Unit Terkait]
    J --> L[Pengusul Melihat di Panel]
    K --> L
    L --> M{Monitoring}
    M --> N[Dashboard Pengusul - Read Only]
    M --> O[Dashboard Direksi - Read Only]
    M --> P[Dashboard Verifikator - Full Access]
    P --> Q{Verifikator Action}
    Q -->|Edit Data| R[Update Info SOP]
    Q -->|Delete| S[Soft Delete ke Sampah]
    R --> T[Create Riwayat Perubahan]
    S --> U[Bisa Restore atau Hapus Permanen]
    T --> V{Masa Berlaku}
    V -->|< 3 Tahun| W[Status: AKTIF]
    V -->|>= 3 Tahun| X[Status: KADALUARSA]
    W --> M
    X --> Y[Hilang dari Landing Page]
```

### Alur Notifikasi

1. **Verifikator Terbitkan SOP Baru**

    - Verifikator upload PDF, pilih unit pemilik, dan isi data lengkap
    - Sistem otomatis set status **AKTIF**
    - Sistem create riwayat di `tb_riwayat_sop` (aksi: "SOP Diterbitkan")
    - Sistem broadcast notifikasi berdasarkan jenis SOP:
        - **SOP Regular:** Notifikasi hanya ke Pengusul/Kepala Unit Pemilik
        - **SOP AP:** Notifikasi ke Pengusul Unit Pemilik + semua Pengusul Unit Terkait
    - Kepala Unit melihat notifikasi "SOP Baru Diterbitkan" di panel mereka
    - SOP langsung tampil di Landing Page publik

2. **Verifikator Edit SOP**

    - Sistem update data dokumen
    - Sistem create riwayat perubahan dengan detail yang diubah
    - Notifikasi dikirim ke unit terkait (sesuai jenis SOP)

3. **Verifikator Delete SOP**

    - Soft delete SOP ke "Sampah"
    - Sistem create riwayat "SOP Dihapus"
    - SOP hilang dari panel Pengusul dan Landing Page
    - Verifikator bisa restore atau hapus permanen

4. **SOP Mencapai Masa Review (1 Tahun)**

    - Sistem menandai SOP perlu review (belum diimplementasi auto-check)
    - Verifikator dapat filter SOP yang perlu review
    - Verifikator melakukan review dan update jika perlu

5. **SOP Kadaluarsa (3 Tahun)**
    - Status otomatis berubah menjadi **KADALUARSA**
    - SOP hilang dari landing page publik
    - SOP masih tampil di panel internal dengan status KADALUARSA
    - Dashboard menampilkan statistik SOP kadaluarsa
    - Verifikator dapat menerbitkan revisi SOP baru

### Alur Login & Authentication

1. **User Mengakses Halaman Login**

    - Buka http://localhost:8000/login (satu halaman login untuk semua role)
    - Tampilan login dengan branding RSUP Prof. I.G.N.G. Ngoerah

2. **User Memasukkan Kredensial**

    - Input email dan password
    - Klik tombol "Masuk"

3. **Sistem Validasi & Redirect Otomatis**

    - Sistem melakukan autentikasi via Laravel Authentication
    - Sistem membaca role akun yang login (Admin, Pengusul, Verifikator, Direksi)
    - **Redirect otomatis** ke panel sesuai role:
        - Role `admin` → `/admin` (Panel Super Admin)
        - Role `pengusul` → `/pengusul` (Panel Pengusul)
        - Role `verifikator` → `/verifikator` (Panel Verifikator)
        - Role `direksi` → `/direksi` (Panel Direksi)

4. **User Masuk ke Dashboard**
    - Langsung masuk ke panel sesuai hak akses
    - Tidak perlu memilih role atau panel secara manual

### Alur Password Reset

1. **User Lupa Password**

    - Klik link "Lupa Password?" di halaman login (`/login`)
    - Masukkan email yang terdaftar di sistem
    - Klik "Kirim Link Reset Password"

2. **Sistem Kirim Email via SMTP Gmail**

    - Laravel generate token reset password (valid 60 menit)
    - Sistem kirim email ke Gmail user menggunakan SMTP
    - Email template custom dengan branding RSUP
    - Link reset: `http://localhost:8000/password/reset/{token}?email={user_email}`

3. **User Menerima Email**

    - Buka inbox Gmail
    - Email dari "RSUP Prof. Dr. I.G.N.G. Ngoerah" (sesuai konfigurasi MAIL_FROM)
    - Klik tombol/link "Reset Password"

4. **User Reset Password**

    - Browser membuka halaman reset password
    - Form berisi: Email (readonly), Password Baru, Konfirmasi Password
    - Input password baru (minimal 8 karakter)
    - Klik "Reset Password"

5. **Sistem Update Password & Redirect**
    - Sistem validasi token (belum expired & valid)
    - Update password user di database (hashed dengan bcrypt)
    - Hapus token reset dari database
    - Redirect ke halaman login dengan notifikasi sukses
    - User bisa login dengan password baru

---

## 🛠️ Tech Stack

-   **Framework:** [Laravel 12](https://laravel.com) - PHP Framework terbaru
-   **Admin Panel:** [Filament V3](https://filamentphp.com) - Modern Admin Panel
-   **Frontend Logic:** Livewire 3 & Alpine.js - Reactive Components
-   **Styling:**
    -   Tailwind CSS (CDN untuk Landing Page)
    -   Filament Native CSS untuk Admin Panel
-   **Database:** MySQL 8.0+
-   **PDF Processing:**
    -   Smalot PDF Parser - Ekstraksi teks dari PDF
    -   Native Browser PDF Viewer - Preview PDF
-   **AI Integration:** Google Gemini 2.5 Flash API - AI Summary
-   **Real-time:** Laravel Broadcasting (Database Driver)
-   **Email:** SMTP Gmail - Notifikasi email
-   **Queue:** Database Driver - Background Jobs
-   **Session:** Database Driver - Session Management

---

## 🚀 Instalasi & Penggunaan (Local Development)

Ikuti langkah ini untuk menjalankan projek di komputer lokal Anda:

### Prasyarat

-   **PHP >= 8.2** (Pastikan extension: `pdo_mysql`, `mbstring`, `xml`, `curl`, `zip`, `gd`)
-   **Composer** (Dependency Manager PHP)
-   **Node.js >= 18.x & NPM** (Package Manager JavaScript)
-   **MySQL >= 8.0** atau MariaDB
-   **Laragon/XAMPP/WAMP** (Opsional untuk Windows)

### Langkah-langkah Instalasi

#### 1. Clone Repository

```bash
git clone https://github.com/akfiss/sim-sop-app.git
cd sim-sop-app
```

#### 2. Install Dependencies

```bash
# Install PHP Dependencies
composer install

# Install JavaScript Dependencies
npm install
```

#### 3. Setup Environment

Duplikat file `.env.example` menjadi `.env`:

**Windows:**

```bash
copy .env.example .env
```

**Linux/Mac:**

```bash
cp .env.example .env
```

#### 4. Konfigurasi Database

Buka file `.env` dan atur konfigurasi database:

```env
APP_NAME="SIM-SOP RSUP Ngoerah"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_simsop
DB_USERNAME=root
DB_PASSWORD=
```

**Catatan:** Pastikan database `db_simsop` sudah dibuat di MySQL:

```sql
CREATE DATABASE db_simsop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

#### 5. Generate Application Key & Storage Link

```bash
# Generate encryption key
php artisan key:generate

# Create symbolic link untuk storage
php artisan storage:link
```

#### 6. Jalankan Migration & Seeder

```bash
# Jalankan migration (membuat tabel)
php artisan migrate

# Jalankan seeder (data awal)
php artisan db:seed
```

**Data yang di-seed:**

-   4 Direktorat (Medik, Penunjang Medik, Keperawatan, Umum SDM & Diklat)
-   12 Unit Kerja (IGD, ICU, Rawat Inap, Lab, Radiologi, Farmasi, dll)
-   User Default (lihat bagian [User Default](#-user-default))
-   5 Dokumen SOP contoh untuk testing

#### 7. Build Frontend Assets

```bash
# Development mode (dengan watch)
npm run dev

# Production mode (optimized)
npm run build
```

#### 8. Jalankan Aplikasi

**Opsi A: Menggunakan Laravel Artisan**

```bash
# Terminal 1: Run Web Server
php artisan serve

# Terminal 2: Run Queue Worker (untuk notifikasi & email)
php artisan queue:work

# Terminal 3: Run Vite Dev Server (jika pakai npm run dev)
npm run dev
```

**Opsi B: Menggunakan Composer Script (Recommended)**

```bash
# Menjalankan server, queue, dan vite secara bersamaan
composer run dev
```

Script ini akan menjalankan 3 proses secara parallel:

-   Web Server (localhost:8000)
-   Queue Worker (background jobs)
-   Vite Dev Server (hot reload)

**Opsi C: Menggunakan Laragon**

-   Start Apache & MySQL di Laragon
-   Akses via: `http://sim-sop-app.test`
-   Jalankan queue worker di terminal: `php artisan queue:work`

#### 9. Akses Aplikasi

Buka browser dan akses:

-   **Landing Page:** http://localhost:8000
-   **Login (Semua Role):** http://localhost:8000/login
-   **Panduan:** http://localhost:8000/panduan

**Cara Login:**

1. Buka halaman login di http://localhost:8000/login
2. Masukkan email dan password sesuai akun yang ingin digunakan
3. Sistem akan otomatis mendeteksi role akun Anda
4. Setelah login berhasil, Anda akan diarahkan ke panel sesuai role:
    - **Admin** → Panel Admin (`/admin`)
    - **Pengusul** → Panel Pengusul (`/pengusul`)
    - **Verifikator** → Panel Verifikator (`/verifikator`)
    - **Direksi** → Panel Direksi (`/direksi`)

---

## ⚙️ Konfigurasi Fitur Tambahan

### 1. Setup Google Gemini API (AI Summary)

Fitur AI Summary menggunakan Google Gemini 2.5 Flash untuk membuat ringkasan otomatis dari dokumen SOP.

#### Langkah Setup:

1. **Dapatkan API Key dari Google AI Studio**

    - Kunjungi: https://aistudio.google.com/apikey
    - Login dengan akun Google Anda
    - Klik **"Create API Key"**
    - Pilih project (atau buat project baru)
    - Copy API Key yang dihasilkan

2. **Tambahkan ke File `.env`**

    Buka file `.env` dan tambahkan baris berikut:

    ```env
    GOOGLE_AI_API_KEY=AIzaSyXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
    ```

    **Ganti `AIzaSyXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX` dengan API Key Anda!**

3. **Clear Cache**

    ```bash
    php artisan config:clear
    php artisan cache:clear
    ```

4. **Test Fitur AI Summary**
    - Buka landing page
    - Klik salah satu dokumen SOP
    - Klik tombol **"Ringkasan AI"** dengan icon sparkle (✨)
    - Loading akan muncul dan AI akan generate ringkasan dalam bentuk bullet points

#### Catatan Penting:

-   **Free Tier:** Google Gemini 2.5 Flash memiliki free tier 15 requests/menit
-   **Rate Limit:** Jika terlalu banyak request, akan muncul error 429
-   **Token Limit:** Sistem membatasi teks PDF maksimal 30,000 karakter untuk efisiensi
-   **Error Handling:** Jika PDF tidak bisa dibaca (scan/gambar), sistem akan memberikan error
-   **Timeout:** Request timeout 30 detik untuk mencegah hanging

#### Troubleshooting AI Summary:

| Error                    | Solusi                                                                             |
| ------------------------ | ---------------------------------------------------------------------------------- |
| "API Key belum dipasang" | Pastikan `GOOGLE_AI_API_KEY` ada di `.env` dan jalankan `php artisan config:clear` |
| "Gagal membaca file PDF" | File PDF rusak atau terpassword, upload ulang file                                 |
| "Teks tidak terbaca"     | PDF berupa scan/gambar, tidak bisa diproses (perlu OCR)                            |
| "Rate limit exceeded"    | Tunggu 1 menit atau upgrade ke paid tier                                           |
| "Safety Block"           | Konten PDF dianggap tidak aman oleh Gemini, coba dokumen lain                      |

### 2. Setup SMTP Gmail (Reset Password via Email)

Fitur reset password mengirim link reset ke email user menggunakan SMTP Gmail.

#### Langkah Setup:

1. **Aktifkan 2-Step Verification di Gmail**

    - Buka: https://myaccount.google.com/security
    - Cari **"2-Step Verification"**
    - Klik dan ikuti langkah aktivasi

2. **Generate App Password**

    - Setelah 2-Step Verification aktif, buka: https://myaccount.google.com/apppasswords
    - Pilih **"Select app"** → Pilih **"Mail"**
    - Pilih **"Select device"** → Pilih **"Other"** → Ketik **"SIM-SOP App"**
    - Klik **"Generate"**
    - Copy App Password yang dihasilkan (16 karakter tanpa spasi)

3. **Konfigurasi File `.env`**

    Buka file `.env` dan edit bagian MAIL:

    ```env
    MAIL_MAILER=smtp
    MAIL_HOST=smtp.gmail.com
    MAIL_PORT=587
    MAIL_USERNAME=your-email@gmail.com
    MAIL_PASSWORD=your-16-char-app-password
    MAIL_ENCRYPTION=tls
    MAIL_FROM_ADDRESS=your-email@gmail.com
    MAIL_FROM_NAME="SIM-SOP RSUP Ngoerah"
    ```

    **Ganti:**

    - `your-email@gmail.com` → Email Gmail Anda
    - `your-16-char-app-password` → App Password 16 karakter (tanpa spasi)

4. **Clear Cache**

    ```bash
    php artisan config:clear
    php artisan cache:clear
    ```

5. **Test Reset Password**
    - Buka halaman login mana saja
    - Klik **"Lupa Password?"**
    - Masukkan email user yang terdaftar
    - Cek inbox Gmail
    - Klik link reset password di email
    - Masukkan password baru

#### Contoh Konfigurasi Lengkap:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=admin.simsop@gmail.com
MAIL_PASSWORD=abcdeffghijklmno
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=admin.simsop@gmail.com
MAIL_FROM_NAME="SIM-SOP RSUP Ngoerah"
```

#### Troubleshooting Email:

| Error                    | Solusi                                                                            |
| ------------------------ | --------------------------------------------------------------------------------- |
| "Could not authenticate" | App Password salah atau 2-Step Verification belum aktif                           |
| "Connection timeout"     | Firewall/Antivirus memblokir port 587, coba port 465 dengan `MAIL_ENCRYPTION=ssl` |
| "Email not sent"         | Queue worker tidak berjalan, jalankan `php artisan queue:work`                    |
| "Email tidak masuk"      | Cek folder Spam/Junk di Gmail                                                     |

#### Template Email:

Sistem menggunakan custom template email di: `resources/views/emails/reset-password.blade.php`

Template email berisi:

-   Logo RSUP
-   Judul yang jelas
-   Tombol "Reset Password" yang menonjol
-   Link alternatif jika tombol tidak berfungsi
-   Informasi expiry (60 menit)
-   Warning untuk keamanan

### 3. Setup Queue Worker (Notifikasi Realtime)

Queue worker penting untuk menjalankan notifikasi dan email di background.

#### Development:

```bash
# Jalankan queue worker
php artisan queue:work

# Atau dengan auto-reload saat code berubah
php artisan queue:listen
```

#### Production (Ubuntu/Linux Server):

1. **Install Supervisor**

    ```bash
    sudo apt-get install supervisor
    ```

2. **Buat Config File**

    ```bash
    sudo nano /etc/supervisor/conf.d/sim-sop-worker.conf
    ```

3. **Isi Config:**

    ```ini
    [program:sim-sop-worker]
    process_name=%(program_name)s_%(process_num)02d
    command=php /path/to/sim-sop-app/artisan queue:work --sleep=3 --tries=3
    autostart=true
    autorestart=true
    user=www-data
    numprocs=2
    redirect_stderr=true
    stdout_logfile=/path/to/sim-sop-app/storage/logs/worker.log
    ```

4. **Reload Supervisor**
    ```bash
    sudo supervisorctl reread
    sudo supervisorctl update
    sudo supervisorctl start sim-sop-worker:*
    ```

### 4. Setup Broadcasting (Notifikasi Realtime)

Saat ini sistem menggunakan database driver untuk broadcasting. Untuk notifikasi yang lebih realtime, bisa upgrade ke Pusher atau Laravel Reverb.

#### Current Setup (Database):

```env
BROADCAST_CONNECTION=log
QUEUE_CONNECTION=database
```

#### Upgrade ke Pusher (Optional):

1. **Install Pusher SDK**

    ```bash
    composer require pusher/pusher-php-server
    ```

2. **Daftar di Pusher.com**

    - Buat akun gratis di https://pusher.com
    - Create new app
    - Copy credentials

3. **Update `.env`**

    ```env
    BROADCAST_CONNECTION=pusher
    PUSHER_APP_ID=your-app-id
    PUSHER_APP_KEY=your-app-key
    PUSHER_APP_SECRET=your-app-secret
    PUSHER_APP_CLUSTER=ap1
    ```

4. **Update Frontend**
    - Install Laravel Echo & Pusher JS
    - Uncomment broadcasting code di `resources/js/bootstrap.js`

---

## 👥 User Default

Setelah menjalankan `php artisan db:seed`, sistem membuat user default berikut:

| Role            | Email                  | Password    | Unit Kerja    |
| --------------- | ---------------------- | ----------- | ------------- |
| **Super Admin** | admin@rsup.go.id       | password123 | -             |
| **Pengusul 1**  | kepala.igd@rsup.go.id  | password123 | IGD           |
| **Pengusul 2**  | kepala.icu@rsup.go.id  | password123 | ICU           |
| **Pengusul 3**  | kepala.ok@rsup.go.id   | password123 | Kamar Operasi |
| **Verifikator** | verifikator@rsup.go.id | password123 | -             |
| **Direksi**     | direksi@rsup.go.id     | password123 | -             |

### Cara Login:

1. **Buka Halaman Login:** http://localhost:8000/login
2. **Masukkan Kredensial:** Gunakan email dan password sesuai tabel di atas
3. **Login Otomatis Redirect:** Sistem akan mengarahkan ke panel sesuai role:
    - Admin → `/admin` (Dashboard Super Admin)
    - Pengusul → `/pengusul` (Dashboard Pengusul)
    - Verifikator → `/verifikator` (Dashboard Verifikator)
    - Direksi → `/direksi` (Dashboard Direksi)

**Contoh:**

-   Login dengan `admin@rsup.go.id` → Diarahkan ke Panel Admin
-   Login dengan `kepala.igd@rsup.go.id` → Diarahkan ke Panel Pengusul
-   Login dengan `verifikator@rsup.go.id` → Diarahkan ke Panel Verifikator

**PENTING:** Segera ganti semua password default setelah deployment ke production!

---

## 🐛 Troubleshooting

### Problem: Error 500 setelah clone

**Solusi:**

```bash
php artisan key:generate
php artisan config:clear
php artisan cache:clear
php artisan view:clear
chmod -R 775 storage bootstrap/cache
```

### Problem: Storage link tidak berfungsi

**Solusi:**

```bash
# Hapus link lama
rm public/storage

# Buat link baru
php artisan storage:link

# Windows (jika error)
php artisan storage:link --force
```

### Problem: Migration error "table already exists"

**Solusi:**

```bash
# Reset database
php artisan migrate:fresh

# Dengan seeder
php artisan migrate:fresh --seed
```

### Problem: PDF tidak ter-upload

**Solusi:**

-   Cek permission folder `storage/app/public/dokumen-sop`
-   Cek ukuran file (max 10MB)
-   Cek format file (hanya PDF)
-   Cek `php.ini`: `upload_max_filesize` dan `post_max_size`

### Problem: Notifikasi tidak muncul

**Solusi:**

-   Pastikan queue worker berjalan: `php artisan queue:work`
-   Clear cache: `php artisan config:clear`
-   Restart queue worker: Ctrl+C lalu jalankan ulang

### Problem: Email tidak terkirim

**Solusi:**

-   Cek konfigurasi SMTP di `.env`
-   Cek App Password Gmail valid
-   Pastikan queue worker berjalan
-   Cek log: `storage/logs/laravel.log`
-   Test koneksi SMTP:
    ```bash
    php artisan tinker
    Mail::raw('Test', function($message) {
        $message->to('test@example.com')->subject('Test');
    });
    ```

### Problem: AI Summary error "API Key belum dipasang"

**Solusi:**

```bash
# Pastikan GOOGLE_AI_API_KEY ada di .env
php artisan config:clear
php artisan cache:clear

# Test API Key
php artisan tinker
dd(config('services.google'));
```

### Problem: Vite assets tidak load

**Solusi:**

```bash
# Development
npm run dev

# Production
npm run build
php artisan config:clear
```

### Problem: Permission denied (Linux/Mac)

**Solusi:**

```bash
# Set proper permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Untuk development (jika www-data tidak ada)
sudo chmod -R 777 storage bootstrap/cache
```

---

## 📚 Dokumentasi Tambahan

### Struktur Database

**Tabel Utama:**

-   `tb_direktorat` - Master data direktorat
-   `tb_unit_kerja` - Master data unit kerja (relasi ke direktorat)
-   `tb_users` - Data user dengan role (admin, pengusul, verifikator, direksi)
-   `tb_dokumen_sop` - Dokumen SOP utama
-   `tb_sop_unit_terkait` - Relasi many-to-many untuk SOP AP
-   `tb_riwayat_sop` - History tracking semua perubahan SOP
-   `tb_notifikasi` - Notifikasi untuk user

### File Penting

**Backend:**

-   `app/Models/DokumenSop.php` - Model dokumen SOP dengan relasi
-   `app/Observers/DokumenSopObserver.php` - Observer untuk auto-logging
-   `app/Livewire/LoncengNotifikasi.php` - Komponen notifikasi
-   `app/Events/NewNotification.php` - Event broadcasting notifikasi
-   `app/Notifications/CustomResetPassword.php` - Custom reset password email

**Frontend:**

-   `resources/views/landing-page.blade.php` - Halaman publik
-   `resources/views/guide/` - Halaman panduan untuk setiap role
-   `resources/views/livewire/lonceng-notifikasi.blade.php` - UI notifikasi
-   `resources/views/emails/reset-password.blade.php` - Template email reset

**Filament Resources:**

-   `app/Filament/Pengusul/Resources/` - Resource untuk panel pengusul
-   `app/Filament/Verifikator/Resources/` - Resource untuk panel verifikator
-   `app/Filament/Direksi/Resources/` - Resource untuk panel direksi
-   `app/Filament/SuperAdmin/Resources/` - Resource untuk panel admin

### Menambah User Baru Manual

Jika ingin menambah user via database langsung:

```sql
INSERT INTO tb_users (nama, email, password, role, id_unit, email_verified_at, created_at, updated_at)
VALUES (
    'Nama User',
    'email@example.com',
    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
    'pengusul', -- atau: verifikator, direksi, admin
    'UNIT-xxxxx', -- ID unit kerja (null untuk admin/direksi)
    NOW(),
    NOW(),
    NOW()
);
```

**Atau via Artisan Tinker:**

```bash
php artisan tinker
```

```php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::create([
    'nama' => 'Nama User',
    'email' => 'email@example.com',
    'password' => Hash::make('password123'),
    'role' => 'pengusul',
    'id_unit' => 'UNIT-xxxxx'
]);
```

---

## 🚀 Deployment ke Production

### Checklist Deployment:

1. **Environment Production**

    ```env
    APP_ENV=production
    APP_DEBUG=false
    APP_URL=https://simsop.rsupngoerah.go.id
    ```

2. **Optimize Laravel**

    ```bash
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan optimize
    ```

3. **Build Assets Production**

    ```bash
    npm run build
    ```

4. **Setup Queue Worker dengan Supervisor**

    - Lihat [Setup Queue Worker](#3-setup-queue-worker-notifikasi-realtime)

5. **Setup Backup Database**

    ```bash
    # Crontab untuk backup harian
    0 2 * * * mysqldump -u root -p db_simsop > /backup/simsop_$(date +\%Y\%m\%d).sql
    ```

6. **Setup SSL Certificate**

    - Gunakan Let's Encrypt atau SSL dari provider
    - Update `APP_URL` dengan https

7. **Ganti Semua Password Default**

    - Login sebagai admin
    - Update password semua user

8. **Setup Monitoring**
    - Laravel Telescope (development)
    - Log monitoring
    - Uptime monitoring

---

## 🤝 Kontribusi

Projek ini dikembangkan untuk RSUP Prof. Dr. I.G.N.G. Ngoerah. Untuk kontribusi atau pertanyaan, hubungi tim IT RSUP.

---

## 📄 Lisensi

Projek ini adalah milik RSUP Prof. Dr. I.G.N.G. Ngoerah dan dilindungi oleh hak cipta internal.

---

## 👨‍💻 Developer

Dikembangkan oleh **Akbar Johan Firdaus** bagian Tim IT RSUP Prof. Dr. I.G.N.G. Ngoerah

**Tech Stack:**

-   Laravel 12 (PHP 8.2+)
-   Filament V3
-   Livewire 3
-   Alpine.js
-   Tailwind CSS
-   MySQL 8
-   Google Gemini 2.5 Flash API

---

## 📞 Support

Untuk bantuan teknis atau pertanyaan, silakan hubungi:

-   **Email:** akbar.firdaus2205@gmail.com
-   **Repository:** https://github.com/akfiss/sim-sop-app
-   **Documentation:** http://localhost:8000/panduan

---

**© 2026 RSUP Prof. Dr. I.G.N.G. Ngoerah - Sistem Informasi Manajemen SOP**
