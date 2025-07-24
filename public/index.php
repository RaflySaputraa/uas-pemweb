<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang di Kota Anda</title>
    <link rel="stylesheet" href="assets/css/style.css">
    </head>
<body>
    <header>
        <div class="container">
            <a href="index.php" class="logo">Purwokerto</a>
            <div class="menu-toggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <nav>
                <ul>
                    <li><a href="index.php">Beranda</a></li>
                    <li><a href="profil.php">Profil Kota</a></li>
                    <li><a href="layanan.php">Layanan</a></li>
                    <li><a href="galeri.php">Galeri</a></li>
                    <li><a href="kontak.php">Kontak</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section class="hero">
            <video autoplay muted loop class="hero-video-bg">
                <source src="assets/img/vidio pw.mp4" type="video/mp4">
            </video>
            <div class="hero-content">
                <h1>Jelajahi Pesona Kota Kami</h1>
                <p>Temukan informasi lengkap tentang profil, layanan publik, dan keindahan kota yang tak terlupakan.</p>
                <a href="profil.php" class="btn">Mulai Jelajahi <i class="fas fa-arrow-right"></i></a>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <h2>Pengumuman Terbaru</h2>
                <div class="news-grid">
                    <div class="news-card service-card"> <i class="fas fa-newspaper"></i>
                        <h3>Peresmian Pusat Inovasi Baru</h3>
                        <p>Pusat inovasi terbaru kota telah resmi dibuka, diharapkan mendorong pertumbuhan ekonomi digital.</p>
                    </div>
                    <div class="news-card service-card">
                        <i class="fas fa-bullhorn"></i>
                        <h3>Program Vaksinasi Massal Lanjutan</h3>
                        <p>program vaksinasi massal untuk seluruh warga kota, diharapkan datang menuju alun - alun jam 9 pagi</p>
                    </div>
                    <div class="news-card service-card">
                        <i class="fas fa-calendar-alt"></i>
                        <h3>Festival Budaya Kota Tahunan</h3>
                        <p>Jangan lewatkan festival budaya tahunan kami yang akan menampilkan berbagai seni dan kuliner lokal di alun - alun jam 5 sore.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="section" style="background-color: var(--secondary-color);">
            <div class="container">
                <h2>Layanan Populer</h2>
                <div class="service-grid">
                    <div class="service-card">
                        <i class="fas fa-id-card"></i>
                        <h3>Kartu Keluarga</h3>
                        <p>Pengurusan dokumen Kartu Keluarga baru, perubahan, atau penggantian.</p>
                        <a href="layanan.php" class="btn" style="background-color: #6c757d; padding: 8px 15px; font-size: 0.9em; border-radius: 5px; border: none;">Detail</a>
                    </div>
                    <div class="service-card">
                        <i class="fas fa-id-badge"></i>
                        <h3>KTP Elektronik</h3>
                        <p>Pengurusan KTP elektronik baru atau penggantian.</p>
                        <a href="layanan.php" class="btn" style="background-color: #6c757d; padding: 8px 15px; font-size: 0.9em; border-radius: 5px; border: none;">Detail</a>
                    </div>
                    <div class="service-card">
                        <i class="fas fa-briefcase-medical"></i>
                        <h3>Pelayanan Kesehatan</h3>
                        <p>Informasi dan akses ke fasilitas pelayanan kesehatan di kota.</p>
                        <a href="layanan.php" class="btn" style="background-color: #6c757d; padding: 8px 15px; font-size: 0.9em; border-radius: 5px; border: none;">Detail</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>Hak Cipta &copy; 2025 Purwokerto. Semua Hak Dilindungi.</p>
        </div>
    </footer>

    <script src="assets/js/script.js"></script>
</body>
</html>