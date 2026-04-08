# Transisi ke React SPA dengan Estetika Naruto x Jujutsu Kaisen

Rencana ini merangkum perombakan UAS Platform dari aplikasi Laravel berbasis Blade menjadi React Single Page Application (SPA) modern dengan tema Dashboard SaaS unik "Naruto x Jujutsu Kaisen" yang ditargetkan untuk Gen Z.

## Perlu Peninjauan Pengguna

> [!IMPORTANT]
> Ini adalah perubahan arsitektur besar. Tampilan Blade yang ada saat ini akan diganti dengan frontend React. Backend hanya akan berfungsi sebagai API.

> [!CAUTION]
> Integrasi dengan Midtrans (Pembayaran) memerlukan penanganan khusus untuk memastikan pengalihan (redirect) Snap berjalan lancar di dalam SPA.

## Usulan Perubahan

### 1. Kerangka Kerja & Infrastruktur
*   **Frontend**: React (Vite) + Tailwind CSS + Zustand (State) + Axios (API) + React Router.
*   **Backend**: Laravel dengan Sanctum untuk otentikasi token.
*   **Tema**: "Infinite Sage" (Naruto x JJK) - Mode gelap secara default dengan aksen oranye dan ungu/biru yang cerah.

---

### 2. Branding & Desain UI/UX (Naruto x JJK)
*   **Palet Warna**:
    *   `Utama (Oranye)`: Sage Mode Naruto / Energi Kyuubi (#FF9F1C)
    *   `Sekunder (Ungu Tua/Biru)`: "Hollow Purple" Gojo / Domain Sukuna (#6A0572 / #240046)
    *   `Latar Belakang`: Mode gelap ramping dengan glassmorphism (#0D0D14)
*   **Elemen UI**:
    *   **Loading**: Animasi Sharingan yang berputar atau pusaran Energi Terkutuk (Cursed Energy).
    *   **Kartu (Cards)**: Border bercahaya (efek Energi Terkutuk).
    *   **Tipografi**: Sans-serif modern dan tebal (Inter/Outfit).
    *   **Animasi**: Framer Motion untuk transisi "Jutsu" yang halus.

---

### 3. Arsitektur Frontend [BARU]
File akan disusun di bawah direktori `resources/js` (menggunakan integrasi Laravel Vite).
*   `/resources/js/components`: `Button.tsx`, `Sidebar.tsx`, `StatCard.tsx`, `LoadingJutsu.tsx`.
*   `/resources/js/pages`: `Login.tsx`, `Admin/Dashboard.tsx`, `Karyawan/Dashboard.tsx`, `Order/History.tsx`.
*   `/resources/js/store`: `authStore.ts` (Zustand).
*   `/resources/js/services`: `api.ts` (Instance Axios), `orderService.ts`, `userService.ts`.

---

### 4. Adaptasi API Backend [UBAH]
*   **Setup Sanctum**: Konfigurasi `Sanctum` untuk otentikasi SPA.
*   **API Routes**: Pindahkan/Duplikasi logika dari `web.php` ke `api.php`.
*   **Controllers**: Buat/Perbarui controller untuk mengembalikan `JsonResponse`.
*   **Middleware**: Pastikan `Auth:sanctum` melindungi API.

#### [UBAH] [api.php](file:///c:/UAS_Platform/routes/api.php)
#### [BARU] [Api Controllers](file:///c:/UAS_Platform/app/Http/Controllers/Api/...)

---

## Pertanyaan Terbuka

1.  **Gaya Deployment**: Apakah Anda ingin aplikasi React terintegrasi dengan Laravel (Vite) atau sebagai proyek terpisah (standalone)? (Disarankan terintegrasi untuk stack ini).
2.  **Alur Pembayaran**: Apakah pembayaran Midtrans harus dibuka dalam modal atau dialihkan ke halaman "Sukses" khusus di React?
3.  **Nada Konten**: Apakah UI harus menggunakan terminologi tema anime (misalnya, "Misi" alih-alih "Pesanan", "Pengguna" sebagai "Shinobi/Penyihir")?

## Rencana Verifikasi

### Tes Otomatis
*   `npm run test` (Frontend Vitest) - untuk logika inti.
*   `php artisan test` (Backend) - untuk memastikan endpoint API mengembalikan JSON yang valid.

### Verifikasi Manual
*   Alur Login (Sanctum session/token).
*   Pengambilan data Dashboard.
*   Pemeriksaan responsivitas (Tampilan mobile).
*   Kontrol akses berbasis peran (Role-based access control).
