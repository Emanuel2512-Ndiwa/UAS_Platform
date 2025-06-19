import './bootstrap';

// Cek apakah ada token di localStorage
const authLink = document.getElementById('auth-link');

// Fungsi untuk update status menu (Masuk / Logout)
function updateAuthLink() {
    const token = localStorage.getItem('token');
    if (token) {
        // Jika token ada, berarti sudah login
        authLink.textContent = 'Keluar';  // Ubah tombol menjadi "Keluar"
        authLink.setAttribute('href', '#');
        authLink.addEventListener('click', logout); // Tambahkan event listener logout
    } else {
        // Jika tidak ada token, tampilkan "Masuk"
        authLink.textContent = 'Masuk';
        authLink.setAttribute('href', '/login');
        authLink.removeEventListener('click', logout); // Hapus event listener logout jika tidak login
    }
}

// Fungsi logout
function logout() {
    localStorage.removeItem('token');  // Hapus token dari localStorage
    updateAuthLink();  // Update status link
    alert('Anda telah keluar!');
    window.location.href = '/';  // Arahkan ke halaman utama setelah logout
}

// Panggil fungsi updateAuthLink saat halaman dimuat
document.addEventListener('DOMContentLoaded', updateAuthLink);
