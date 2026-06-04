// API Base URL
const API_BASE = 'http://localhost/bayangtrans/api';

// Get semua kendaraan
async function getKendaraanFromDB() {
    try {
        const response = await fetch(`${API_BASE}/kendaraan.php`);
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error fetching kendaraan:', error);
        return [];
    }
}

// Update ketersediaan
async function updateKetersediaan(id, tersedia) {
    try {
        const response = await fetch(`${API_BASE}/kendaraan.php`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id, tersedia })
        });
        return await response.json();
    } catch (error) {
        console.error('Error updating ketersediaan:', error);
        return { success: false };
    }
}

// Update harga
async function updateHarga(id, harga) {
    try {
        const response = await fetch(`${API_BASE}/kendaraan.php`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id, harga })
        });
        return await response.json();
    } catch (error) {
        console.error('Error updating harga:', error);
        return { success: false };
    }
}

// Login admin
async function loginAdmin(username, password) {
    try {
        const response = await fetch(`${API_BASE}/login.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
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
        const response = await fetch(`${API_BASE}/login.php`);
        return await response.json();
    } catch (error) {
        return { logged_in: false };
    }
}

// Logout
async function logoutAdmin() {
    try {
        await fetch(`${API_BASE}/login.php`, { method: 'DELETE' });
        sessionStorage.clear();
    } catch (error) {
        console.error('Error logout:', error);
    }
}

// Submit booking
async function submitBooking(data) {
    try {
        const response = await fetch(`${API_BASE}/booking.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        return await response.json();
    } catch (error) {
        console.error('Error submit booking:', error);
        return { success: false };
    }
}