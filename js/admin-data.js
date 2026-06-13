// ===== DATABASE BAYANGTRANS - LOCALSTORAGE =====

const defaultKendaraan = {
    motor: [
        { id: 1, nama: "Beat Old", harga: 60000, tersedia: true, gambar: "https://images.unsplash.com/photo-1629814484931-37300b2b0816?auto=format&fit=crop&w=600&q=80", fitur: ["2 Helm", "Jas Hujan", "Bensin penuh"], rating: 4.7 },
        { id: 2, nama: "Beat New", harga: 70000, tersedia: true, gambar: "https://images.unsplash.com/photo-1629814484931-37300b2b0816?auto=format&fit=crop&w=600&q=80", fitur: ["2 Helm", "Jas Hujan", "Bensin penuh"], rating: 4.7 },
        { id: 3, nama: "Beat Street", harga: 75000, tersedia: true, gambar: "https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=600&q=80", fitur: ["2 Helm", "Jas Hujan", "Bensin penuh"], rating: 4.7 },
        { id: 4, nama: "All New Scoopy", harga: 85000, tersedia: true, gambar: "https://images.unsplash.com/photo-1591635591007-22909559a689?auto=format&fit=crop&w=600&q=80", fitur: ["2 Helm", "Jas Hujan", "Bensin penuh"], rating: 4.8 },
        { id: 5, nama: "New Vario", harga: 100000, tersedia: true, gambar: "https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=600&q=80", fitur: ["2 Helm", "Jas Hujan", "Bensin penuh"], rating: 4.8 },
        { id: 6, nama: "All-new Vario", harga: 110000, tersedia: true, gambar: "https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=600&q=80", fitur: ["2 Helm", "Jas Hujan", "Bensin penuh"], rating: 4.8 },
        { id: 7, nama: "Stylo", harga: 120000, tersedia: true, gambar: "https://images.unsplash.com/photo-1609521263047-f8f205293f24?auto=format&fit=crop&w=600&q=80", fitur: ["2 Helm", "Jas Hujan", "Bensin penuh"], rating: 4.8 },
        { id: 8, nama: "Nmax", harga: 130000, tersedia: true, gambar: "https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=600&q=80", fitur: ["2 Helm", "Jas Hujan", "Bensin penuh"], rating: 4.9 }
    ],
    mobil: [
        { id: 11, nama: "Avanza Grand", harga: 300000, tersedia: true, gambar: "https://images.unsplash.com/photo-1590362891991-f776e747a588?auto=format&fit=crop&w=600&q=80", fitur: ["Bensin penuh", "Supir opsional", "Free car seat"], rating: 4.6 },
        { id: 12, nama: "Mobilio", harga: 300000, tersedia: true, gambar: "https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&w=600&q=80", fitur: ["Bensin penuh", "Supir opsional", "Free car seat"], rating: 4.7 },
        { id: 13, nama: "Ayla", harga: 275000, tersedia: true, gambar: "https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&w=600&q=80", fitur: ["Bensin penuh", "Supir opsional", "Free car seat"], rating: 4.5 },
        { id: 14, nama: "Sigra", harga: 275000, tersedia: true, gambar: "https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&w=600&q=80", fitur: ["Bensin penuh", "Supir opsional", "Free car seat"], rating: 4.5 },
        { id: 15, nama: "New Xenia", harga: 350000, tersedia: true, gambar: "https://images.unsplash.com/photo-1590362891991-f776e747a588?auto=format&fit=crop&w=600&q=80", fitur: ["Bensin penuh", "Supir opsional", "Free car seat"], rating: 4.6 },
        { id: 16, nama: "Inova Grand", harga: 400000, tersedia: true, gambar: "https://images.unsplash.com/photo-1609521263047-f8f205293f24?auto=format&fit=crop&w=600&q=80", fitur: ["Bensin penuh", "Supir opsional", "Free car seat"], rating: 4.9 },
        { id: 17, nama: "Inova Reborn", harga: 550000, tersedia: true, gambar: "https://images.unsplash.com/photo-1609521263047-f8f205293f24?auto=format&fit=crop&w=600&q=80", fitur: ["Bensin penuh", "Supir opsional", "Free car seat"], rating: 4.9 },
        { id: 18, nama: "Brio", harga: 300000, tersedia: true, gambar: "https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&w=600&q=80", fitur: ["Bensin penuh", "Supir opsional", "Free car seat"], rating: 4.7 }
    ]
};

const defaultAdmin = {
    username: "admin",
    password: "bayangtrans2024"
};

// Initialize Database
function initDatabase() {
    if (!localStorage.getItem('bayangtrans_kendaraan')) {
        localStorage.setItem('bayangtrans_kendaraan', JSON.stringify(defaultKendaraan));
    }
    if (!localStorage.getItem('bayangtrans_admin')) {
        localStorage.setItem('bayangtrans_admin', JSON.stringify(defaultAdmin));
    }
    if (!localStorage.getItem('bayangtrans_booking')) {
        localStorage.setItem('bayangtrans_booking', JSON.stringify([]));
    }
}

// Get Data
function getKendaraan() {
    return JSON.parse(localStorage.getItem('bayangtrans_kendaraan')) || defaultKendaraan;
}

function getAdmin() {
    return JSON.parse(localStorage.getItem('bayangtrans_admin')) || defaultAdmin;
}

function getBooking() {
    return JSON.parse(localStorage.getItem('bayangtrans_booking')) || [];
}

// Update Functions
function updateKendaraan(data) {
    localStorage.setItem('bayangtrans_kendaraan', JSON.stringify(data));
}

function updateKetersediaan(id, tersedia) {
    const data = getKendaraan();
    const all = [...data.motor, ...data.mobil];
    const item = all.find(k => k.id === id);
    if (item) {
        item.tersedia = tersedia;
        updateKendaraan(data);
        return true;
    }
    return false;
}

function updateHarga(id, harga) {
    const data = getKendaraan();
    const all = [...data.motor, ...data.mobil];
    const item = all.find(k => k.id === id);
    if (item) {
        item.harga = parseInt(harga);
        updateKendaraan(data);
        return true;
    }
    return false;
}

function updateGambar(id, gambarBase64) {
    const data = getKendaraan();
    const all = [...data.motor, ...data.mobil];
    const item = all.find(k => k.id === id);
    if (item) {
        item.gambar = gambarBase64;
        updateKendaraan(data);
        return true;
    }
    return false;
}

// Booking Functions
function addBooking(bookingData) {
    const bookings = getBooking();
    bookingData.id = bookings.length > 0 ? Math.max(...bookings.map(b => b.id)) + 1 : 1;
    bookingData.status = 'Pending';
    bookingData.created_at = new Date().toISOString();
    bookings.push(bookingData);
    localStorage.setItem('bayangtrans_booking', JSON.stringify(bookings));
    return bookingData;
}

function updateBookingStatus(id, status) {
    const bookings = getBooking();
    const booking = bookings.find(b => b.id === id);
    if (booking) {
        booking.status = status;
        localStorage.setItem('bayangtrans_booking', JSON.stringify(bookings));
        return true;
    }
    return false;
}

// Login Function
function loginAdmin(username, password) {
    const admin = getAdmin();
    if (username === admin.username && password === admin.password) {
        sessionStorage.setItem('admin_logged_in', 'true');
        sessionStorage.setItem('admin_username', username);
        sessionStorage.setItem('admin_name', 'Administrator');
        return true;
    }
    return false;
}

function isAdminLoggedIn() {
    return sessionStorage.getItem('admin_logged_in') === 'true';
}

function logoutAdmin() {
    sessionStorage.removeItem('admin_logged_in');
    sessionStorage.removeItem('admin_username');
    sessionStorage.removeItem('admin_name');
}

// Convert File to Base64
function fileToBase64(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = () => resolve(reader.result);
        reader.onerror = error => reject(error);
    });
}

// Initialize on load
initDatabase();