-- Database: bayangtrans_db
-- Created for BayangTrans Rental Kendaraan

CREATE DATABASE IF NOT EXISTS `bayangtrans_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `bayangtrans_db`;

-- ========================================
-- TABEL KENDARAAN
-- ========================================
CREATE TABLE `kendaraan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `tipe` enum('Motor','Mobil') NOT NULL,
  `harga` int(11) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `fitur` text DEFAULT NULL,
  `rating` decimal(2,1) DEFAULT 4.5,
  `status` enum('Tersedia','Tidak Tersedia') DEFAULT 'Tersedia',
  `stok` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================================
-- TABEL BOOKING
-- ========================================
CREATE TABLE `booking` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `instagram` varchar(100) NOT NULL,
  `facebook` varchar(100) DEFAULT NULL,
  `nohp1` varchar(20) NOT NULL,
  `nowa2` varchar(20) NOT NULL,
  `norek` varchar(50) DEFAULT NULL,
  `pekerjaan` varchar(100) NOT NULL,
  `foto_ktp` varchar(255) DEFAULT NULL,
  `tgl_mulai` date NOT NULL,
  `jam_mulai` time NOT NULL,
  `lokasi_mulai` varchar(255) NOT NULL,
  `tgl_selesai` date NOT NULL,
  `jam_selesai` time NOT NULL,
  `lokasi_selesai` varchar(255) NOT NULL,
  `kendaraan_id` int(11) NOT NULL,
  `jumlah` int(11) DEFAULT 1,
  `identitas1` varchar(50) NOT NULL,
  `identitas2` varchar(50) NOT NULL,
  `identitas3` varchar(50) NOT NULL,
  `dp` varchar(50) DEFAULT '(Diisi Admin)',
  `pelunasan` enum('Cash','Transfer') DEFAULT 'Cash',
  `plat_kendaraan` varchar(20) DEFAULT '(Diisi Admin)',
  `wa_pengantar` varchar(20) DEFAULT '(Diisi Admin)',
  `status` enum('Pending','DP Diterima','Aktif','Selesai','Batal') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `kendaraan_id` (`kendaraan_id`),
  CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`kendaraan_id`) REFERENCES `kendaraan` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================================
-- TABEL ADMIN (untuk login admin)
-- ========================================
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================================
-- DATA AWAL: KENDARAAN
-- ========================================
INSERT INTO `kendaraan` (`nama`, `tipe`, `harga`, `gambar`, `fitur`, `rating`, `stok`) VALUES
('Honda Scoopy', 'Motor', 90000, 'https://images.unsplash.com/photo-1591635591007-22909559a689?auto=format&fit=crop&w=600&q=80', 'Retro Style,2 Helm SNI,Irit BBM', 4.8, 3),
('Honda Beat Deluxe', 'Motor', 70000, 'https://images.unsplash.com/photo-1629814484931-37300b2b0816?auto=format&fit=crop&w=600&q=80', 'Desain Elegan,2 Helm SNI,Bagasi Luas', 4.7, 4),
('Honda Beat Street', 'Motor', 75000, 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=600&q=80', 'Sporty Look,2 Helm SNI,Ringan', 4.7, 3),
('Honda Beat CW', 'Motor', 60000, 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=600&q=80', 'Classic White,2 Helm SNI,Nyaman', 4.6, 5),
('Honda Stylo', 'Motor', 100000, 'https://images.unsplash.com/photo-1609521263047-f8f205293f24?auto=format&fit=crop&w=600&q=80', 'Premium Design,2 Helm SNI,Keyless', 4.8, 2),
('Honda ADV 160', 'Motor', 135000, 'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?auto=format&fit=crop&w=600&q=80', 'Adventure Style,2 Helm SNI,Suspensi Empuk', 4.9, 2),
('Honda PCX 160', 'Motor', 135000, 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=600&q=80', 'Maxi Scooter,2 Helm SNI,Sangat Nyaman', 4.9, 2),
('Toyota Innova', 'Mobil', 450000, 'https://images.unsplash.com/photo-1609521263047-f8f205293f24?auto=format&fit=crop&w=600&q=80', '8 Penumpang,AC Double Blower,Mewah', 4.9, 1),
('Honda Brio', 'Mobil', 350000, 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&w=600&q=80', '5 Penumpang,City Car,Irit BBM', 4.7, 2),
('Daihatsu Xenia', 'Mobil', 400000, 'https://images.unsplash.com/photo-1590362891991-f776e747a588?auto=format&fit=crop&w=600&q=80', '7 Penumpang,Bagasi Luas,AC Dingin', 4.6, 1),
('Honda Mobilio', 'Mobil', 300000, 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&w=600&q=80', '7 Penumpang,Sporty MPV,Nyaman', 4.7, 1);

-- ========================================
-- DATA AWAL: ADMIN
-- Password default: admin123 (sudah di-hash)
-- ========================================
INSERT INTO `admin` (`username`, `password`, `nama`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator BayangTrans');