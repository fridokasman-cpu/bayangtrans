document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('kendaraan-grid');
    const filterBtns = document.querySelectorAll('.filter-btn');
    let allVehicles = [];

    // 1. Fetch Data dari Database via PHP API
    fetch('api/kendaraan.php')
        .then(response => response.json())
        .then(data => {
            allVehicles = data;
            renderVehicles(data);
        })
        .catch(error => {
            console.error('Error loading data:', error);
            container.innerHTML = '<p style="text-align:center; width:100%; color:red;">Gagal memuat data kendaraan. Pastikan XAMPP MySQL sudah running.</p>';
        });

    // 2. Fungsi Render Kartu
    function renderVehicles(vehicles) {
        container.innerHTML = '';
        
        if (vehicles.length === 0) {
            container.innerHTML = '<p style="text-align:center; width:100%;">Tidak ada kendaraan tersedia.</p>';
            return;
        }

        vehicles.forEach(vehicle => {
            const card = document.createElement('div');
            card.className = 'vehicle-card fade-in';
            card.innerHTML = `
                <div class="vehicle-badge ${vehicle.tipe === 'Mobil' ? 'popular' : ''}">${vehicle.tipe}</div>
                <div class="vehicle-img" style="background: url('${vehicle.gambar}') center/cover;"></div>
                <div class="vehicle-info">
                    <h3>${vehicle.nama}</h3>
                    <div class="vehicle-rating">
                        ${'<i class="fas fa-star"></i>'.repeat(Math.floor(vehicle.rating))}
                        <span>(${vehicle.rating})</span>
                    </div>
                    <div class="vehicle-price">
                        <span class="price">Rp ${vehicle.harga.toLocaleString('id-ID')}</span>
                        <span class="period">/hari</span>
                    </div>
                    <ul class="vehicle-features">
                        ${vehicle.fitur.map(f => `<li><i class="fas fa-check"></i> ${f}</li>`).join('')}
                    </ul>
                    <a href="booking.html?kendaraan=${encodeURIComponent(vehicle.nama)}" class="btn-book">Book Sekarang</a>
                </div>
            `;
            container.appendChild(card);
        });
        
        observeFadeElements();
    }

    // 3. Fungsi Filter
    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const filter = btn.getAttribute('data-filter');
            if (filter === 'all') {
                renderVehicles(allVehicles);
            } else {
                const filtered = allVehicles.filter(v => v.tipe === filter);
                renderVehicles(filtered);
            }
        });
    });

    function observeFadeElements() {
        const fadeElements = document.querySelectorAll('.fade-in');
        const fadeObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    fadeObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        fadeElements.forEach(el => fadeObserver.observe(el));
    }
});