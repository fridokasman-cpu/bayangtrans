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
        form.addEventListener('submit', (e) => {
            e.preventDefault();

            // Ambil semua data
            const nama = document.getElementById('nama').value.trim();
            const instagram = document.getElementById('instagram').value.trim();
            const facebook = document.getElementById('facebook').value.trim() || '-';
            const nohp1 = document.getElementById('nohp1').value.trim();
            const nowa2 = document.getElementById('nowa2').value.trim();
            const norek = document.getElementById('norek').value.trim() || '-';
            const pekerjaan = document.getElementById('pekerjaan').value.trim();
            const fotoKTP = document.getElementById('fotoktp').files[0];
            
            const tglMulaiVal = document.getElementById('tgl-mulai').value;
            const jamMulai = document.getElementById('jam-mulai').value;
            const lokasiMulai = document.getElementById('lokasi-mulai').value.trim();
            const tglSelesaiVal = document.getElementById('tgl-selesai').value;
            const jamSelesai = document.getElementById('jam-selesai').value;
            const lokasiSelesai = document.getElementById('lokasi-selesai').value.trim();
            
            const jenisKendaraan = document.getElementById('jenis-kendaraan').value;
            const jumlahKendaraan = document.getElementById('jumlah-kendaraan').value;
            
            const identitas1 = document.getElementById('identitas1').value;
            const identitas2 = document.getElementById('identitas2').value;
            const identitas3 = document.getElementById('identitas3').value;
            
            const agree = document.getElementById('agree').checked;

            // Validasi
            if (!nama || !instagram || !nohp1 || !nowa2 || !pekerjaan) {
                alert('Mohon lengkapi semua data yang wajib diisi!');
                return;
            }

            if (!fotoKTP) {
                alert('Mohon upload Foto KTP!');
                return;
            }

            if (!tglMulaiVal || !jamMulai || !lokasiMulai || !tglSelesaiVal || !jamSelesai || !lokasiSelesai) {
                alert('Mohon lengkapi detail penyewaan!');
                return;
            }

            if (!jenisKendaraan || !jumlahKendaraan) {
                alert('Mohon pilih jenis kendaraan dan jumlah!');
                return;
            }

            if (!identitas1 || !identitas2 || !identitas3) {
                alert('Mohon pilih minimal 3 identitas jaminan!');
                return;
            }

            if (!agree) {
                alert('Mohon centang persetujuan ketentuan!');
                return;
            }

            // Format tanggal ke Indonesia
            const formatTanggal = (dateStr) => {
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                return new Date(dateStr).toLocaleDateString('id-ID', options);
            };

            // Format Pesan WhatsApp yang rapi
            const pesan = `📋 *FORM BOOKING BAYANGTRANS*%0A` +
                          `━━━━━━━━━━━━━━━━━━━━%0A%0A` +
                          
                          ` *DATA PEMESAN*%0A` +
                          `• Nama: ${nama}%0A` +
                          `• Instagram: ${instagram}%0A` +
                          `• Facebook: ${facebook}%0A` +
                          `• No HP: ${nohp1}%0A` +
                          `• No WA: ${nowa2}%0A` +
                          `• No Rekening: ${norek}%0A` +
                          `• Pekerjaan: ${pekerjaan}%0A` +
                          `• Foto KTP: ✅ Terlampir (akan dikirim setelah ini)%0A%0A` +
                          
                          `📅 *DETAIL PENYEWAAN*%0A` +
                          `• Mulai: ${formatTanggal(tglMulaiVal)}, ${jamMulai} WIB%0A` +
                          `• Lokasi Mulai: ${lokasiMulai}%0A` +
                          `• Selesai: ${formatTanggal(tglSelesaiVal)}, ${jamSelesai} WIB%0A` +
                          `• Lokasi Selesai: ${lokasiSelesai}%0A` +
                          `• Kendaraan: ${jenisKendaraan}%0A` +
                          `• Jumlah: ${jumlahKendaraan} unit%0A%0A` +
                          
                          `🪪 *IDENTITAS JAMINAN*%0A` +
                          `1. ${identitas1}%0A` +
                          `2. ${identitas2}%0A` +
                          `3. ${identitas3}%0A%0A` +
                          
                          `━━━━━━━━━━━━━━━━━━━━%0A` +
                          `Saya telah membaca dan menyetujui semua ketentuan.%0A` +
                          `Mohon konfirmasi ketersediaan dan info DP.%0A%0A` +
                          `Terima kasih `;

            // Buka WhatsApp dengan pesan
            const nomorAdmin = '6281263860005';
            const waURL = `https://wa.me/${nomorAdmin}?text=${pesan}`;
            
            // Tampilkan instruksi kirim foto KTP
            setTimeout(() => {
                alert('✅ Form berhasil dikirim!\n\n📸 JANGAN LUPA:\nKirim Foto KTP Anda via WhatsApp ke admin setelah ini.\n\nTerima kasih!');
            }, 500);

            window.open(waURL, '_blank');
        });
    }
});