// Tampilkan nama file yang diupload
function showFileName(input) {
    const fileName = document.getElementById('file-name');
    if (input.files && input.files[0]) {
        fileName.textContent = '✓ ' + input.files[0].name;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('booking-form');

    // Set minimum date ke hari ini
    const today = new Date().toISOString().split('T')[0];
    const tglMulai = document.getElementById('tgl-mulai');
    const tglSelesai = document.getElementById('tgl-selesai');
    
    if (tglMulai) tglMulai.min = today;
    if (tglSelesai) tglSelesai.min = today;

    // Update min tanggal selesai saat tanggal mulai berubah
    if (tglMulai && tglSelesai) {
        tglMulai.addEventListener('change', () => {
            tglSelesai.min = tglMulai.value;
            if (tglSelesai.value && tglSelesai.value < tglMulai.value) {
                tglSelesai.value = tglMulai.value;
            }
        });
    }

    // Handle Submit Form
    if (form) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            // Ambil semua data
            const data = {
                nama: document.getElementById('nama').value.trim(),
                instagram: document.getElementById('instagram').value.trim(),
                facebook: document.getElementById('facebook').value.trim(),
                nohp1: document.getElementById('nohp1').value.trim(),
                nowa2: document.getElementById('nowa2').value.trim(),
                norek: document.getElementById('norek').value.trim(),
                pekerjaan: document.getElementById('pekerjaan').value.trim(),
                tgl_mulai: document.getElementById('tgl-mulai').value,
                jam_mulai: document.getElementById('jam-mulai').value,
                lokasi_mulai: document.getElementById('lokasi-mulai').value.trim(),
                tgl_selesai: document.getElementById('tgl-selesai').value,
                jam_selesai: document.getElementById('jam-selesai').value,
                lokasi_selesai: document.getElementById('lokasi-selesai').value.trim(),
                kendaraan_nama: document.getElementById('jenis-kendaraan').value,
                jumlah: document.getElementById('jumlah-kendaraan').value,
                identitas1: document.getElementById('identitas1').value,
                identitas2: document.getElementById('identitas2').value,
                identitas3: document.getElementById('identitas3').value,
                catatan: ''
            };

            // Validasi
            if (!data.nama || !data.instagram || !data.nohp1 || !data.nowa2 || !data.pekerjaan) {
                alert('Mohon lengkapi semua data yang wajib diisi!');
                return;
            }

            if (!document.getElementById('fotoktp').files[0]) {
                alert('Mohon upload Foto KTP!');
                return;
            }

            if (!data.tgl_mulai || !data.jam_mulai || !data.lokasi_mulai || 
                !data.tgl_selesai || !data.jam_selesai || !data.lokasi_selesai) {
                alert('Mohon lengkapi detail penyewaan!');
                return;
            }

            if (!data.kendaraan_nama || !data.jumlah) {
                alert('Mohon pilih jenis kendaraan dan jumlah!');
                return;
            }

            if (!data.identitas1 || !data.identitas2 || !data.identitas3) {
                alert('Mohon pilih minimal 3 identitas jaminan!');
                return;
            }

            if (!document.getElementById('agree').checked) {
                alert('Mohon centang persetujuan ketentuan!');
                return;
            }

            // ===== SIMPAN KE DATABASE DULU =====
            const btnSubmit = document.querySelector('.btn-submit');
            const originalText = btnSubmit.innerHTML;
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan booking...';

            try {
                const response = await fetch('api/booking.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    // Simpan berhasil, lanjut ke WhatsApp
                    btnSubmit.innerHTML = '<i class="fas fa-check"></i> Booking tersimpan! Mengarahkan ke WhatsApp...';
                    
                    setTimeout(() => {
                        kirimKeWhatsApp(data);
                    }, 1000);
                } else {
                    throw new Error(result.error || 'Gagal menyimpan booking');
                }
            } catch (error) {
                console.error('Error:', error);
                
                // Jika database gagal, tetap lanjut ke WhatsApp (fallback)
                const lanjutkan = confirm(
                    'Gagal menyimpan ke database.\n\n' +
                    'Apakah Anda tetap ingin melanjutkan booking via WhatsApp?\n\n' +
                    'Error: ' + error.message
                );
                
                if (lanjutkan) {
                    kirimKeWhatsApp(data);
                } else {
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML = originalText;
                }
            }
        });
    }
});

// Fungsi kirim ke WhatsApp
function kirimKeWhatsApp(data) {
    const formatTanggal = (dateStr) => {
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(dateStr).toLocaleDateString('id-ID', options);
    };

    const pesan = `📋 *FORM BOOKING BAYANGTRANS*%0A` +
                  `━━━━━━━━━━━━━━━━━━━━%0A%0A` +
                  `👤 *DATA PEMESAN*%0A` +
                  `• Nama: ${data.nama}%0A` +
                  `• Instagram: ${data.instagram}%0A` +
                  `• Facebook: ${data.facebook || '-'}%0A` +
                  `• No HP: ${data.nohp1}%0A` +
                  `• No WA: ${data.nowa2}%0A` +
                  `• No Rekening: ${data.norek || '-'}%0A` +
                  `• Pekerjaan: ${data.pekerjaan}%0A` +
                  `• Foto KTP: ✅ Akan dikirim setelah ini%0A%0A` +
                  `📅 *DETAIL PENYEWAAN*%0A` +
                  `• Mulai: ${formatTanggal(data.tgl_mulai)}, ${data.jam_mulai} WIB%0A` +
                  `• Lokasi Mulai: ${data.lokasi_mulai}%0A` +
                  `• Selesai: ${formatTanggal(data.tgl_selesai)}, ${data.jam_selesai} WIB%0A` +
                  `• Lokasi Selesai: ${data.lokasi_selesai}%0A` +
                  `• Kendaraan: ${data.kendaraan_nama}%0A` +
                  `• Jumlah: ${data.jumlah} unit%0A%0A` +
                  `🪪 *IDENTITAS JAMINAN*%0A` +
                  `1. ${data.identitas1}%0A` +
                  `2. ${data.identitas2}%0A` +
                  `3. ${data.identitas3}%0A%0A` +
                  `━━━━━━━━━━━━━━━━━━━━%0A` +
                  `Saya telah membaca dan menyetujui semua ketentuan.%0A` +
                  `Mohon konfirmasi ketersediaan dan info DP.%0A%0A` +
                  `Terima kasih 🙏`;

    // Tampilkan instruksi kirim foto KTP
    setTimeout(() => {
        alert('✅ Booking berhasil disimpan!\n\n📸 JANGAN LUPA:\nKirim Foto KTP Anda via WhatsApp ke admin setelah ini.\n\nTerima kasih!');
    }, 500);

    const nomorAdmin = '6281263860005';
    window.open(`https://wa.me/${nomorAdmin}?text=${pesan}`, '_blank');
}