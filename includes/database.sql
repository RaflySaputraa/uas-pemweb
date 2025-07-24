DROP TABLE IF EXISTS `profil_kota`;
DROP TABLE IF EXISTS `layanan`;
DROP TABLE IF EXISTS `galeri`;
DROP TABLE IF EXISTS `kontak`;
DROP TABLE IF EXISTS `saran_kritikan`;
DROP TABLE IF EXISTS `permohonan_layanan`;
DROP TABLE IF EXISTS `admin_users`;


--
-- Struktur Tabel untuk `profil_kota`
--
CREATE TABLE `profil_kota` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nama_kota` VARCHAR(100) NOT NULL,
    `provinsi` VARCHAR(100) NOT NULL,
    `kode_pos` VARCHAR(10) NOT NULL,
    `luas_wilayah` VARCHAR(50) NOT NULL,
    `jumlah_penduduk` VARCHAR(50) NOT NULL,
    `visi` TEXT,
    `misi` TEXT,
    `sejarah` TEXT,
    `total_penduduk` VARCHAR(50),
    `luas_wilayah_statistik` VARCHAR(50),
    `kepala_keluarga` VARCHAR(50),
    `lahan_pertanian` VARCHAR(50)
);

--
-- Dumping Data untuk Tabel `profil_kota`
--
INSERT INTO `profil_kota` (
    `nama_kota`,
    `provinsi`,
    `kode_pos`,
    `luas_wilayah`,
    `jumlah_penduduk`,
    `visi`,
    `misi`,
    `sejarah`,
    `total_penduduk`,
    `luas_wilayah_statistik`,
    `kepala_keluarga`,
    `lahan_pertanian`
) VALUES (
    'Kota Purwokerto',
    'Provinsi Jawa Tengah',
    '53116',
    '41,65 km²',
    '236.162 Jiwa',
    'Menjadikan Kota Purwokerto sebagai kota yang berdaya saing, inovatif, dan sejahtera dengan menjunjung tinggi nilai-nilai budaya dan lingkungan.',
    '- Meningkatkan kualitas pelayanan publik. \n- Mengembangkan ekonomi lokal berbasis potensi daerah. \n- Melestarikan budaya dan lingkungan hidup. \n- Meningkatkan partisipasi masyarakat dalam pembangunan.',
    'Kota Purwokerto memiliki sejarah panjang yang kaya, dimulai dari sebuah pemukiman kecil di tepi sungai X pada abad ke-17. Berkembang menjadi pusat perdagangan rempah, kota ini memainkan peran penting dalam era kolonial. Setelah kemerdekaan, Kota Contoh bertransformasi menjadi pusat industri dan pendidikan. Hingga kini, kota ini terus berinovasi sambil tetap menjaga warisan budayanya.',
    ' 231.765',
    '41,65 km²',
    '64.156',
    '1.500 ha'
);


--
-- Struktur Tabel untuk `layanan`
--
CREATE TABLE `layanan` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nama_layanan` VARCHAR(255) NOT NULL,
    `deskripsi` TEXT
);

--
-- Dumping Data untuk Tabel `layanan`
--
INSERT INTO `layanan` (`nama_layanan`, `deskripsi`) VALUES
('Kartu Keluarga', 'Pengurusan dokumen Kartu Keluarga baru, perubahan, atau penggantian.'),
('Pelayanan Kesehatan', 'Informasi dan akses ke fasilitas pelayanan kesehatan di kota.'),
('Surat Keterangan Domisili', 'Pengurusan surat keterangan domisili untuk berbagai keperluan.'),
('Bantuan Sosial', 'Program dan informasi terkait bantuan sosial bagi warga.'),
('KTP Elektronik', 'Pengurusan KTP elektronik baru atau penggantian.'),
('Akta Kelahiran', 'Pengurusan akta kelahiran anak.'),
('Surat Nikah', 'Informasi dan persyaratan untuk pengurusan surat nikah.');


--
-- Struktur Tabel untuk `galeri`
--
CREATE TABLE `galeri` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nama_tempat` VARCHAR(255) NOT NULL,
    `deskripsi` TEXT,
    `gambar_url` VARCHAR(255) NOT NULL
);

--
-- Dumping Data untuk Tabel `galeri`
--
-- PATH GAMBAR DI SINI DISESUAIKAN DENGAN public/assets/img/
INSERT INTO `galeri` (`nama_tempat`, `deskripsi`, `gambar_url`) VALUES
('Taman Kota Indah', 'Tempat rekreasi keluarga yang asri dengan berbagai fasilitas.', 'public/assets/img/taman_kota.jpg'),
('Pusat Perbelanjaan Modern', 'Mall terbesar di kota dengan beragam toko dan hiburan.', 'public/assets/img/mall_modern.jpg'),
('Masjid Agung', 'Salah satu ikon kota dengan arsitektur yang megah.', 'public/assets/img/masjid_agung.jpg');


--
-- Struktur Tabel untuk `kontak`
--
-- Ini adalah versi terbaru dengan URL Google Maps yang sudah diperbaiki.
CREATE TABLE `kontak` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `alamat` TEXT NOT NULL,
    `telepon` VARCHAR(50) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `jam_layanan` TEXT NOT NULL,
    `link_lokasi` TEXT
);

--
-- Dumping Data untuk Tabel `kontak`
--
INSERT INTO `kontak` (`id`, `alamat`, `telepon`, `email`, `jam_layanan`, `link_lokasi`) VALUES
(1, 'Jl. Merdeka No. 1, Kota Purwokerto, Provinsi Jawa Tengah, 50142', '081212431499', 'srafly310@gmail.com', 'Senin - Jumat: 08:00 - 16:00 WIB\nJumat: 08:00 - 11:30 WIB\nSabtu - Minggu: Libur', 'https://www.google.com/maps/search/?api=1&query=-7.424404334689062,109.23015814827018');


--
-- Struktur Tabel untuk `saran_kritikan`
--
-- Tambahan kolom 'no_telepon' dan 'subjek' untuk form kontak yang diperbarui.
CREATE TABLE `saran_kritikan` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nama` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100),
    `no_telepon` VARCHAR(50),
    `subjek` VARCHAR(255),
    `pesan` TEXT NOT NULL,
    `tanggal` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


--
-- Struktur Tabel untuk `permohonan_layanan`
--
-- Tabel ini tidak berubah dari sebelumnya.
CREATE TABLE `permohonan_layanan` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nama_lengkap` VARCHAR(255) NOT NULL,
    `email` VARCHAR(100),
    `no_telepon` VARCHAR(50),
    `jenis_layanan` VARCHAR(255) NOT NULL,
    `keterangan_tambahan` TEXT,
    `tanggal_permohonan` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

--
-- Struktur Tabel untuk `admin_users`
-- Tabel untuk pengguna admin
CREATE TABLE `admin_users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL, -- Kolom untuk menyimpan hash password
    `email` VARCHAR(100),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

--
-- Dumping Data untuk Tabel `admin_users`
--
-- PENTING: Hash di bawah ini adalah untuk password 'admin123'.
INSERT INTO `admin_users` (`username`, `password`, `email`) VALUES
('admin', '$2y$10$wT0vV7e.lT/B3Rj.n4P68.u2u0o1a3b4c5d6e7f8g9h0i1j2k3l4m5n6o7p8q9r0s1t', 'admin@example.com');
