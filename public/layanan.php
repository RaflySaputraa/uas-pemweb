<?php
// PASTIKAN KEDUA INCLUDE INI MENGGUNAKAN '../includes/db_config.php'

// INCLUDE PERTAMA: (biasanya baris 2 atau 3)
include '../includes/db_config.php'; 

$layanan_list = [];
$sql = "SELECT * FROM layanan ORDER BY nama_layanan ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $layanan_list[] = $row;
    }
}
$conn->close(); // Koneksi ditutup di sini

// Fungsi untuk mendapatkan ikon berdasarkan nama layanan (tidak berubah)
function getServiceIcon($serviceName) {
    switch (strtolower($serviceName)) {
        case 'kartu keluarga': return 'fas fa-id-card';
        case 'pelayanan kesehatan': return 'fas fa-briefcase-medical';
        case 'surat keterangan domisili': return 'fas fa-file-alt';
        case 'bantuan sosial': return 'fas fa-hand-holding-usd';
        case 'ktp elektronik': return 'fas fa-id-badge';
        case 'akta kelahiran': return 'fas fa-baby';
        case 'surat nikah': return 'fas fa-ring';
        default: return 'fas fa-info-circle';
    }
}

// Handle form submission for Ajukan Permohonan
$message = '';
$message_type = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_permohonan'])) {
    $nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $no_telepon = trim($_POST['no_telepon'] ?? '');
    $jenis_layanan = trim($_POST['jenis_layanan'] ?? '');
    $keterangan_tambahan = trim($_POST['keterangan_tambahan'] ?? '');

    if (!empty($nama_lengkap) && !empty($email) && !empty($jenis_layanan)) {
        // INCLUDE KEDUA: (ini adalah baris yang menyebabkan error di sekitar baris 42)
        // Buka koneksi lagi karena sudah ditutup di atas
        include '../includes/db_config.php'; // PASTIKAN JUGA MENGGUNAKAN '../includes/db_config.php'

        // Pastikan functions.php juga di-include jika digunakan di sini (contohnya sanitize_input)
        // include '../includes/functions.php'; // Tambahkan ini jika sanitize_input() belum terdefinisi

        $stmt = $conn->prepare("INSERT INTO permohonan_layanan (nama_lengkap, email, no_telepon, jenis_layanan, keterangan_tambahan) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nama_lengkap, $email, $no_telepon, $jenis_layanan, $keterangan_tambahan);

        if ($stmt->execute()) {
            $message = "Permohonan Anda berhasil diajukan! Kami akan segera menghubungi Anda.";
            $message_type = "success";
        } else {
            $message = "Terjadi kesalahan saat mengajukan permohonan: " . $stmt->error;
            $message_type = "error";
        }
        $stmt->close();
        $conn->close(); // Koneksi ditutup setelah pemrosesan POST
    } else {
        $message = "Nama Lengkap, Email, dan Jenis Permohonan tidak boleh kosong.";
        $message_type = "error";
    }
}

// Catatan: Jika ada PHP code lain di bawah yang butuh koneksi (misal dropdown layanan),
// koneksi harus dibuka lagi atau tidak ditutup di atas.
// Untuk dropdown jenis layanan, saya akan membiarkan `include '../includes/db_config.php';`
// ada di dalam blok PHP-nya sendiri, itu sudah benar.
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan Kota Anda</title>
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
                <h2>Layanan Publik</h2>
                <?php if (!empty($layanan_list)): ?>
                    <div class="service-grid">
                        <?php foreach ($layanan_list as $layanan): ?>
                            <div class="service-card">
                                <i class="<?php echo getServiceIcon($layanan['nama_layanan']); ?>"></i>
                                <h3><?php echo htmlspecialchars($layanan['nama_layanan']); ?></h3>
                                <p><?php echo htmlspecialchars($layanan['deskripsi']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p style="text-align: center;">Belum ada layanan yang tersedia.</p>
                <?php endif; ?>

                <div class="contact-form-section">
                    <h3><i class="fas fa-check-square"></i> Ajukan Permohonan</h3>
                    <p style="text-align: center; color: var(--light-text-color); margin-bottom: 30px;">
                        Silakan isi form di bawah ini untuk mengajukan permohonan layanan.
                    </p>
                    <?php if (!empty($message)): ?>
                        <div class="alert <?php echo $message_type; ?>">
                            <?php echo $message; ?>
                        </div>
                    <?php endif; ?>
                    <form action="layanan.php" method="POST">
                        <div class="application-form-grid">
                            <div>
                                <label for="nama_lengkap">Nama Lengkap <span style="color: red;">*</span></label>
                                <input type="text" id="nama_lengkap" name="nama_lengkap" placeholder="Cth: Budi Santoso" required>
                            </div>
                            <div>
                                <label for="email_permohonan">Email <span style="color: red;">*</span></label>
                                <input type="email" id="email_permohonan" name="email" placeholder="Cth: nama@email.com" required>
                            </div>
                            <div>
                                <label for="no_telepon">No. Telepon</label>
                                <input type="text" id="no_telepon" name="no_telepon" placeholder="Cth: 081234567890">
                            </div>
                            <div>
                                <label for="jenis_layanan">Jenis Permohonan <span style="color: red;">*</span></label>
                                <select id="jenis_layanan" name="jenis_layanan" required>
                                    <option value="">-- Pilih Jenis Permohonan --</option>
                                    <?php
                                    // INCLUDE KETIGA: (untuk dropdown layanan)
                                    // Buka koneksi lagi untuk mengambil layanan
                                    include '../includes/db_config.php';
                                    $sql_layanan_dropdown = "SELECT nama_layanan FROM layanan ORDER BY nama_layanan ASC";
                                    $result_layanan_dropdown = $conn->query($sql_layanan_dropdown);
                                    if ($result_layanan_dropdown->num_rows > 0) {
                                        while($row_layanan = $result_layanan_dropdown->fetch_assoc()) {
                                            echo "<option value='" . htmlspecialchars($row_layanan['nama_layanan']) . "'>" . htmlspecialchars($row_layanan['nama_layanan']) . "</option>";
                                        }
                                    }
                                    $conn->close();
                                    ?>
                                </select>
                            </div>
                            <div class="full-width">
                                <label for="keterangan_tambahan">Pesan <span style="color: red;">*</span></label>
                                <textarea id="keterangan_tambahan" name="keterangan_tambahan" rows="6" placeholder="Tuliskan pesan Anda di sini..." required></textarea>
                            </div>
                        </div>
                        <button type="submit" name="submit_permohonan">Kirim Permohonan <i class="fas fa-paper-plane"></i></button>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>Hak Cipta &copy; 2025 Kota Anda. Semua Hak Dilindungi.</p>
        </div>
    </footer>

    <script src="assets/js/script.js"></script>
</body>
</html>