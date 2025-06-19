function updateAuthLink() {
    const token = localStorage.getItem('token');
    const authLink = document.getElementById('auth-link');

    if (!authLink) return;

    if (token) {
        authLink.textContent = 'Keluar';  
        authLink.style.backgroundColor = '#CE2941'; 
        authLink.style.color = 'white';
        authLink.style.borderRadius = '20px';
        authLink.style.padding = '10px 20px';
        authLink.setAttribute('href', '#');

        // Hindari duplikasi event listener logout
        authLink.onclick = logout;
    } else {
        authLink.textContent = 'Masuk';
        authLink.setAttribute('href', '/login');
        authLink.style.backgroundColor = '';  
        authLink.style.color = '';
        authLink.style.borderRadius = '';
        authLink.style.padding = '10px 20px';

        // Pastikan tidak menjalankan logout saat belum login
        authLink.onclick = null;
    }

    // Tampilkan tombol setelah selesai setup
    authLink.classList.remove('hidden');
}

async function logout(event) {
    event.preventDefault(); // Cegah navigasi karena href="#"

    const token = localStorage.getItem('token');
    if (!token) {
        alert('Tidak ada sesi aktif.');
        return;
    }

    try {
        const response = await fetch('/api/auth/logout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            },
        });

        if (!response.ok) {
            const data = await response.json();
            alert(data?.message || 'Terjadi kesalahan saat logout');
            return;
        }

        localStorage.removeItem('token');
        updateAuthLink();
        alert('Anda telah keluar!');
        window.location.href = '/';
    } catch (error) {
        console.error('Error saat logout:', error);
        alert('Terjadi kesalahan saat logout.');
    }
}

document.addEventListener('DOMContentLoaded', updateAuthLink);
