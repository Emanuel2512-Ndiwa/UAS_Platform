# Laporan Pembaruan Arsitektur Backend (Backend Overhaul v2)

## 📌 Deskripsi Singkat
Pembaruan ini mencakup perombakan besar-besaran (overhaul) pada arsitektur *backend* untuk sistem UAS_Platform (Laundry Management System). Perubahan utama meliputi penyempurnaan migrasi database, penerapan middleware, restrukturisasi dari sisi "Single-Responsibility" Controller, pemasangan SDK Midtrans untuk otomatisasi pembayaran, serta skema notifikasi WhatsApp dan sinkronisasi logistik (inventaris & GPS Kurir).

---

## 🛠️ Detail Perubahan & Alasan (Why We Did This)

### 1. Database Migrations & Structure
- **Apa yang diubah**: Migrasi direstrukturisasi ulang. Ditambahkan tabel `inventories`, modifikasi total tipe data dan Enum di `transactions`, serta penambahan lokasi geospasial (`latitude`, `longitude`) dan status *suspend/banned* (`is_active`) di tabel `users`.
- **Kenapa**: 
  - Struktur lama belum mengakomodir proses *Offline Walk-in* (pelanggan datang langsung yang tidak perlu register akun, karena itu kita membuat relasi pelanggan/`user_id` menjadi *nullable* dan menggantinya dengan custom string `customer_name_offline`).
  - Parameter geografis diperlukan agar status pengiriman nyata.

### 2. Single-Responsibility Controllers
- **Apa yang diubah**: Menggantikan logika yang sebelumnya terpusat dengan membuat/mengupdate `AuthController`, `AdminController`, `KaryawanController`, `KurirController`, `OrderController`, dan `PaymentController`.
- **Kenapa**: Agar aplikasi mudah ditambah fiturnya dikemudian hari. 
  - `AdminController` murni digunakan untuk mengelola data user, log seluruh *transactions*, dan generate link *broadcast* untuk order yang terlalu lama macet.
  - `KaryawanController` difokuskan pada manajemen *walk-in* dan mengubah *status cucian*, serta memotong otomatis stok inventaris jika *status* cuci selesai.
  - `KurirController` terfokus ke GPS updates secara *realtime* di API server agar Pelanggan tahu estimasi kedatangan kurir menggunakan metode *Haversine Formula*.

### 3. Otomatisasi Pembayaran (Midtrans Service)
- **Apa yang diubah**: Penambahan package `midtrans/midtrans-php` ke *composer*, integrasi file *config* (`config/midtrans.php`), serta konfigurasi Endpoint Webhook di `api/web.php`.
- **Kenapa**: Transaksi tunai memakan banyak biaya risiko bagi karyawan (uang hilang). Midtrans Webhook di backend membuat konfirmasi pembayaran menjadi instan tanpa perlu admin duduk di depan sistem meng-klik *Approve* terus-menerus. Jika user belum membayar > 24 jam, Webhook Midtrans langsung memicu trigger *cancel/expire* di *database server* kita.

### 4. RoleMiddleware & Route Organization
- **Apa yang diubah**: Pembuatan `RoleMiddleware` dan meregistrasikan 43 rute secara *bersih* dan dikumpulkan (*group*) berdasar fungsinya. 
- **Kenapa**: Mencegah insiden *Privilege Escalation* (Pelanggan biasa yang mengakses dashboard Karyawan karena mengetikkan *URL* di browser), ini adalah keamanan mutlak pada suatu platform berbasis autentikasi web.

### 5. API Endpoint (GPS)
- **Apa yang diubah**: Adanya `routes/api.php` di Laravel 12.
- **Kenapa**: Koordinat kurir harus terus dipancarkan biarpun mereka tidak berganti halaman web (melalui sistem AJAX ping dari *browser/HP Kurir*), maka dibuatkan pintu akses tersendiri via Web API untuk efisiensi HTTP Request.

---

## ✅ Status
- Syntax checks lulus 100%.
- Migrasi dieksekusi dengan bersih (Clean SQLite DB).
- Data dummy (seeder) pengguna semua role sukses diuji cobakan.
- Endpoint ter-daftarkan dengan spesifik (`php artisan route:list`).
