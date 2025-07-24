<?php
include '../includes/db_config.php';

$galeri_list = [];
$sql = "SELECT * FROM galeri ORDER BY nama_tempat ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $galeri_list[] = $row;
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri Kota Anda</title>
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
                <h2>Galeri Kota</h2>
                <?php if (!empty($galeri_list)): ?>
                    <div class="gallery-grid">
                        <?php foreach ($galeri_list as $item): ?>
                            <div class="gallery-item">
                                <img src="<?php echo htmlspecialchars($item['gambar_url']); ?>" alt="<?php echo htmlspecialchars($item['nama_tempat']); ?>">
                                <div class="gallery-item-content">
                                    <h3><?php echo htmlspecialchars($item['nama_tempat']); ?></h3>
                                    <p><?php echo htmlspecialchars($item['deskripsi']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p style="text-align: center;">Belum ada item di galeri.</p>
                <?php endif; ?>
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