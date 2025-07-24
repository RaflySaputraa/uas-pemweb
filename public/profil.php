<?php
include '../includes/db_config.php';

$profil = [];
$sql = "SELECT * FROM profil_kota LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $profil = $result->fetch_assoc();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Kota Anda</title>
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
        <section class="section">
            <div class="container">
                <h2>Profil Kota</h2>
                <?php if (!empty($profil)): ?>
                    <div class="profile-info">
                        <div class="profile-item">
                            <i class="fas fa-city"></i>
                            <h3>Nama Kota</h3>
                            <p><?php echo $profil['nama_kota']; ?></p>
                        </div>
                        <div class="profile-item">
                            <i class="fas fa-map-marked-alt"></i>
                            <h3>Provinsi</h3>
                            <p><?php echo $profil['provinsi']; ?></p>
                        </div>
                        <div class="profile-item">
                            <i class="fas fa-mail-bulk"></i>
                            <h3>Kode Pos</h3>
                            <p><?php echo $profil['kode_pos']; ?></p>
                        </div>
                        <div class="profile-item">
                            <i class="fas fa-ruler-combined"></i>
                            <h3>Luas Wilayah</h3>
                            <p><?php echo $profil['luas_wilayah']; ?></p>
                        </div>
                        <div class="profile-item">
                            <i class="fas fa-users"></i>
                            <h3>Jumlah Penduduk</h3>
                            <p><?php echo $profil['jumlah_penduduk']; ?></p>
                        </div>
                    </div>
                <?php else: ?>
                    <p style="text-align: center;">Data profil kota tidak ditemukan.</p>
                <?php endif; ?>
            </div>
        </section>

        <section class="section" style="background-color: var(--secondary-color);">
            <div class="container text-content-section">
                <?php if (!empty($profil['visi'])): ?>
                    <h3>Visi</h3>
                    <p><?php echo nl2br($profil['visi']); ?></p>
                <?php else: ?>
                    <h3>Visi</h3>
                    <p>Menjadikan Kota Anda sebagai pusat inovasi dan kehidupan berkelanjutan, yang nyaman dihuni, maju ekonominya, dan lestari lingkungannya.</p>
                <?php endif; ?>

                <?php if (!empty($profil['misi'])): ?>
                    <h3>Misi</h3>
                    <p><?php echo nl2br($profil['misi']); ?></p>
                <?php else: ?>
                    <h3>Misi</h3>
                    <p>1. Mewujudkan tata kelola pemerintahan yang bersih, efektif, dan melayani.</p>
                    <p>2. Mengembangkan potensi ekonomi lokal melalui UMKM dan pariwisata.</p>
                    <p>3. Meningkatkan kualitas sumber daya manusia dan akses pendidikan serta kesehatan.</p>
                    <p>4. Membangun infrastruktur yang merata dan berkelanjutan.</p>
                    <p>5. Menjaga kelestarian lingkungan hidup dan kekayaan budaya.</p>
                <?php endif; ?>
            </div>
        </section>

        <section class="section">
            <div class="container text-content-section">
                <h3>Sejarah Kota</h3>
                <?php if (!empty($profil['sejarah'])): ?>
                    <p><?php echo nl2br($profil['sejarah']); ?></p>
                <?php else: ?>
                    <p>Kota Anda memiliki akar sejarah yang dalam, berawal dari sebuah permukiman kuno yang strategis di jalur perdagangan. Seiring waktu, kota ini berkembang pesat menjadi pusat kebudayaan dan perdagangan di wilayah ini. Berbagai peninggalan bersejarah masih dapat ditemukan, menceritakan kisah perjalanan panjang kota ini dari masa ke masa. Peran penting kota ini terus berlanjut hingga kini, menjadi saksi bisu perkembangan peradaban dan modernitas.</p>
                    <p>Pada era kemerdekaan, Kota Anda turut serta dalam perjuangan bangsa dan menjadi salah satu simpul penting dalam pembangunan nasional. Dengan semangat gotong royong dan inovasi, masyarakatnya terus membangun dan memajukan kota, menciptakan harmoni antara tradisi dan kemajuan.</p>
                <?php endif; ?>
            </div>
        </section>

        <section class="section" style="background-color: var(--secondary-color);">
            <div class="container">
                <h2>Statistik Kota</h2>
                <div class="stats-grid">
                    <div class="stat-item">
                        <i class="fas fa-users"></i>
                        <div class="stat-value"><?php echo $profil['total_penduduk'] ?? '5,000'; ?></div>
                        <div class="stat-label">Total Penduduk</div>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-map"></i>
                        <div class="stat-value"><?php echo $profil['luas_wilayah_statistik'] ?? '500.00 Ha'; ?></div>
                        <div class="stat-label">Luas Wilayah</div>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-home"></i>
                        <div class="stat-value"><?php echo $profil['kepala_keluarga'] ?? '1250'; ?></div>
                        <div class="stat-label">Kepala Keluarga</div>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-leaf"></i>
                        <div class="stat-value"><?php echo $profil['lahan_pertanian'] ?? '350 Ha'; ?></div>
                        <div class="stat-label">Lahan Pertanian</div>
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