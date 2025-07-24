Fitur Website Publik (User):
Beranda (index.php):
-Halaman selamat datang dengan desain modern dan minimalis.
-Bagian Hero Section dengan video latar belakang (atau gambar) dan teks pengantar.
-Tautan navigasi yang jelas ke bagian-bagian utama website.
-Bagian untuk menampilkan berita/pengumuman terbaru (saat ini statis, bisa dikembangkan dinamis).
-Bagian untuk menampilkan layanan populer (saat ini statis, bisa dikembangkan dinamis).

Profil Kota (profil.php):
-Menampilkan informasi dasar kota: nama, provinsi, kode pos, luas wilayah, jumlah penduduk.
-Menampilkan Visi dan Misi kota.
-Menampilkan Sejarah Kota.
-Menampilkan Statistik Kota (misal: total penduduk, luas wilayah statistik, kepala keluarga, lahan pertanian).

Layanan (layanan.php):
-Menampilkan daftar lengkap layanan publik yang tersedia.
-Setiap layanan memiliki nama dan deskripsi.
-Formulir "Ajukan Permohonan" di bagian bawah halaman untuk mengirimkan permintaan layanan online, mencakup nama, email, nomor telepon, jenis layanan (dropdown), dan keterangan.

Galeri (galeri.php):
-Menampilkan koleksi gambar tempat-tempat menarik atau penting di kota.
-Setiap gambar memiliki nama tempat dan deskripsi.
-Fungsionalitas Lightbox (klik gambar untuk melihat ukuran penuh).

Kontak (kontak.php):
-Menampilkan informasi kontak kantor: alamat, nomor telepon, dan email.
-Menampilkan Jam Layanan kantor.
-Tombol "Lihat Peta" yang akan mengarahkan langsung ke rute Google Maps menuju lokasi kantor.
-Formulir "Saran / Kritikan" untuk masyarakat umum, mencakup nama, email, nomor telepon, subjek, dan pesan.
-Desain Responsif:
-Tampilan website dirancang agar dapat menyesuaikan diri dengan baik di berbagai ukuran layar (desktop, tablet, mobile).

Fitur Panel Admin:
Panel admin dirancang dengan tampilan minimalis, keren, dan modern untuk manajemen konten yang mudah.

Sistem Login & Logout yang Aman:
-Halaman login khusus (admin/index.php) dengan autentikasi username dan password yang di-hash.
-Sistem sesi untuk menjaga status login admin.

Fungsionalitas logout (admin/logout.php).

Dashboard Utama (admin/dashboard.php):
-Halaman beranda panel admin.
-Tampilan selamat datang untuk admin yang login.
-Area untuk menampilkan ringkasan atau statistik penting (misal: jumlah item galeri, permohonan baru, saran/kritikan belum dibaca - ini perlu dihitung secara dinamis).

Modul: Kelola Galeri (CRUD) (admin/galeri.php, admin/galeri_tambah.php, admin/galeri_edit.php):
-Melihat (Read): Menampilkan daftar semua item galeri yang ada, lengkap dengan gambar thumbnail, nama tempat, dan deskripsi singkat.
-Menambah (Create): Formulir untuk menambahkan item galeri baru, termasuk mengunggah file gambar secara langsung.
-Mengedit (Update): Formulir untuk memperbarui informasi item galeri yang sudah ada, termasuk mengganti file gambar.
-Menghapus (Delete): Fungsi untuk menghapus item galeri, yang juga akan menghapus file gambar fisik dari server.

Modul: Kelola Profil Kota (Read & Update) (admin/profil.php, admin/profil_edit.php):
-Melihat (Read): Menampilkan semua detail informasi profil kota, visi, misi, sejarah, dan statistik.
-Mengedit (Update): Formulir lengkap untuk memperbarui semua data profil kota (nama, provinsi, kode pos, luas, penduduk, visi, misi, sejarah, statistik detail).
-Modul: Kelola Layanan (CRUD) (admin/layanan.php, admin/layanan_tambah.php, admin/layanan_edit.php):
-Melihat (Read): Menampilkan daftar semua layanan publik yang terdaftar.
-Menambah (Create): Formulir untuk menambahkan layanan baru (nama dan deskripsi).
-Mengedit (Update): Formulir untuk memperbarui detail layanan yang sudah ada.
-Menghapus (Delete): Fungsi untuk menghapus layanan dari daftar.

Modul: Lihat Saran / Permohonan (Read & Delete) (admin/saran_permohonan.php):
-Melihat (Read): Menampilkan daftar saran/kritikan yang masuk dari publik.
-Melihat (Read): Menampilkan daftar permohonan layanan yang diajukan dari publik.
-Menghapus (Delete): Fungsi untuk menghapus setiap saran/kritikan atau permohonan layanan.
