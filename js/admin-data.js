// Database Kendaraan Default
const defaultKendaraan = {
    motor: [
        { id: 1, nama: "Beat Old", harga: 60000, tersedia: true, gambar: "https://images.unsplash.com/photo-1629814484931-37300b2b0816?auto=format&fit=crop&w=600&q=80", fitur: ["2 Helm", "Jas Hujan", "Bensin penuh"], rating: 4.7 },
        { id: 2, nama: "Beat New", harga: 70000, tersedia: true, gambar: "https://images.unsplash.com/photo-1629814484931-37300b2b0816?auto=format&fit=crop&w=600&q=80", fitur: ["2 Helm", "Jas Hujan", "Bensin penuh"], rating: 4.7 },
        { id: 3, nama: "Beat Street", harga: 75000, tersedia: true, gambar: "https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=600&q=80", fitur: ["2 Helm", "Jas Hujan", "Bensin penuh"], rating: 4.7 },
        { id: 4, nama: "All New Scoopy", harga: 85000, tersedia: true, gambar: "https://images.unsplash.com/photo-1591635591007-22909559a689?auto=format&fit=crop&w=600&q=80", fitur: ["2 Helm", "Jas Hujan", "Bensin penuh"], rating: 4.8 },
        { id: 5, nama: "New Vario", harga: 100000, tersedia: true, gambar: "https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=600&q=80", fitur: ["2 Helm", "Jas Hujan", "Bensin penuh"], rating: 4.8 },
        { id: 6, nama: "All-new Vario", harga: 110000, tersedia: true, gambar: "https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=600&q=80", fitur: ["2 Helm", "Jas Hujan", "Bensin penuh"], rating: 4.8 },
        { id: 7, nama: "Stylo", harga: 120000, tersedia: true, gambar: "https://images.unsplash.com/photo-1609521263047-f8f205293f24?auto=format&fit=crop&w=600&q=80", fitur: ["2 Helm", "Jas Hujan", "Bensin penuh"], rating: 4.8 },
        { id: 8, nama: "Nmax", harga: 130000, tersedia: true, gambar: "https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=600&q=80", fitur: ["2 Helm", "Jas Hujan", "Bensin penuh"], rating: 4.9 },
        { id: 9, nama: "PCX 160", harga: 135000, tersedia: true, gambar: "https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=600&q=80", fitur: ["2 Helm", "Jas Hujan", "Bensin penuh"], rating: 4.9 },
        { id: 10, nama: "ADV 160", harga: 135000, tersedia: true, gambar: "https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?auto=format&fit=crop&w=600&q=80", fitur: ["2 Helm", "Jas Hujan", "Bensin penuh"], rating: 4.9 }
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

// Inisialisasi Database di localStorage
function initDatabase() {
    if (!localStorage.getItem('bayangtrans_kendaraan')) {
        localStorage.setItem('bayangtrans_kendaraan', JSON.stringify(defaultKendaraan));
    }
}

// Get Data Kendaraan
function getKendaraan() {
    return JSON.parse(localStorage.getItem('bayangtrans_kendaraan')) || defaultKendaraan;
}

// Update Ketersediaan
function updateKetersediaan(id, tersedia) {
    const data = getKendaraan();
    const semuaKendaraan = [...data.motor, ...data.mobil];
    const kendaraan = semuaKendaraan.find(k => k.id === id);
    
    if (kendaraan) {
        kendaraan.tersedia = tersedia;
        localStorage.setItem('bayangtrans_kendaraan', JSON.stringify(data));
        return true;
    }
    return false;
}

// Update Harga
function updateHarga(id, harga) {
    const data = getKendaraan();
    const semuaKendaraan = [...data.motor, ...data.mobil];
    const kendaraan = semuaKendaraan.find(k => k.id === id);
    
    if (kendaraan) {
        kendaraan.harga = parseInt(harga);
        localStorage.setItem('bayangtrans_kendaraan', JSON.stringify(data));
        return true;
    }
    return false;
}

// Initialize on load
initDatabase();