// API Base URL - Sesuaikan dengan URL XAMPP kamu
const API_BASE = 'http://localhost/bayangtrans/api';

// Get semua kendaraan dari database
async function getKendaraanFromDB() {
    try {
        const response = await fetch(`${API_BASE}/kendaraan.php`);
        if (!response.ok) throw new Error('Network error');
        return await response.json();
    } catch (error) {
        console.error('Error fetching kendaraan:', error);
        showToast('Gagal memuat data kendaraan!', 'error');
        return [];
    }
}

// Update ketersediaan
async function updateKetersediaan(id, tersedia) {
    try {
        const response = await fetch(`${API_BASE}/kendaraan.php`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            credentials: 'include',
            body: JSON.stringify({ id, tersedia })
        });
        return await response.json();
    } catch (error) {
        console.error('Error updating:', error);
        return { success: false };
    }
}

// Update harga
async function updateHarga(id, harga) {
    try {
        const response = await fetch(`${API_BASE}/kendaraan.php`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            credentials: 'include',
            body: JSON.stringify({ id, harga })
        });
        return await response.json();
    } catch (error) {
        console.error('Error updating:', error);
        return { success: false };
    }
}

// Login admin
async function loginAdmin(username, password) {
    try {
        const response = await fetch(`${API_BASE}/login.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            credentials: 'include',
            body: JSON.stringify({ username, password })
        });
        const data = await response.json();
        if (data.success) {
            sessionStorage.setItem('admin_logged_in', 'true');
            sessionStorage.setItem('admin_username', data.admin.username);
            sessionStorage.setItem('admin_name', data.admin.nama);
        }
        return data;
    } catch (error) {
        console.error('Error login:', error);
        return { success: false, message: 'Koneksi error' };
    }
}

// Cek status login
async function checkLoginStatus() {
    try {
        const response = await fetch(`${API_BASE}/login.php`, {
            credentials: 'include'
        });
        return await response.json();
    } catch (error) {
        return { logged_in: false };
    }
}

// Logout admin
async function logoutAdmin() {
    try {
        await fetch(`${API_BASE}/login.php`, { 
            method: 'DELETE',
            credentials: 'include'
        });
        sessionStorage.clear();
    } catch (error) {
        console.error('Error logout:', error);
    }
}

// Toast helper (jika belum ada di halaman)
function showToast(message, type = 'success') {
    // Implementasi toast bisa di-override di setiap halaman
    console.log(`[${type.toUpperCase()}] ${message}`);
}