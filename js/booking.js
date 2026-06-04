document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('booking-form');
    const kendaraanSelect = document.getElementById('kendaraan-select');
    const urlParams = new URLSearchParams(window.location.search);
    const selectedKendaraan = urlParams.get('kendaraan');

    // 1. Isi dropdown kendaraan & pilih otomatis jika ada di URL
    fetch('data/kendaraan.json')
        .then(res => res.json())
        .then(data => {
            data.forEach(k => {
                const option = document.createElement('option');
                option.value = k.nama;
                option.textContent = `${k.nama} - Rp ${k.harga.toLocaleString('id-ID')}/hari`;
                kendaraanSelect.appendChild(option);
            });

            // Auto select dari URL
            if (selectedKendaraan) {
                kendaraanSelect.value = selectedKendaraan;
            }
        });

    // 2. Handle Submit Form
    if (form) {
        form.addEventListener('submit', (e) => {
            e.preventDefault();

            const nama = document.getElementById('nama').value;
            const wa = document.getElementById('wa').value;
            const tglMulai = document.getElementById('tgl-mulai').value;
            const tglSelesai = document.getElementById('tgl-selesai').value;
            const kendaraan = kendaraanSelect.value;
            const catatan = document.getElementById('catatan').value;

            // Validasi sederhana
            if (!nama || !wa || !tglMulai || !tglSelesai) {
                alert('Mohon lengkapi semua data yang wajib diisi!');
                return;
            }

            // Format Pesan WhatsApp
            const pesan = `Halo BayangTrans, saya ingin booking kendaraan:%0A%0A` +
                          `👤 *Nama:* ${nama}%0A` +
                          `📱 *No WA:* ${wa}%0A` +
                          `🚗 *Kendaraan:* ${kendaraan}%0A` +
                          `📅 *Mulai:* ${tglMulai}%0A` +
                          `📅 *Selesai:* ${tglSelesai}%0A` +
                          ` *Catatan:* ${catatan || '-'}`;

            // Buka WhatsApp
            const nomorAdmin = '6281263860005';
            window.open(`https://wa.me/${nomorAdmin}?text=${pesan}`, '_blank');
        });
    }
});