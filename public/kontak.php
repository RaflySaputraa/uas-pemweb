<?php
include '../includes/db_config.php';

$kontak_info = [];
$sql_kontak = "SELECT * FROM kontak LIMIT 1";
$result_kontak = $conn->query($sql_kontak);

if ($result_kontak->num_rows > 0) {
    $kontak_info = $result_kontak->fetch_assoc();
}

// Handle form submission for saran/kritikan
$message = '';
$message_type = ''; // 'success' or 'error'

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $no_telepon = trim($_POST['no_telepon'] ?? '');
    $subjek = trim($_POST['subjek'] ?? '');
    $pesan = trim($_POST['pesan'] ?? '');

    if (!empty($nama) && !empty($email) && !empty($subjek) && !empty($pesan)) {
        $stmt = $conn->prepare("INSERT INTO saran_kritikan (nama, email, no_telepon, subjek, pesan) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nama, $email, $no_telepon, $subjek, $pesan);

        if ($stmt->execute()) {
            $message = "Pesan Anda berhasil terkirim! Terima kasih atas masukan Anda.";
            $message_type = "success";
        } else {
            $message = "Terjadi kesalahan saat menyimpan pesan Anda: " . $stmt->error;
            $message_type = "error";
        }
        $stmt->close();
    } else {
        $message = "Nama Lengkap, Email, Subjek, dan Pesan tidak boleh kosong. Mohon lengkapi formulir.";
        $message_type = "error";
    }

    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        echo $message;
        exit;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak Kota Anda</title>
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
                <h2>Hubungi Kami</h2>
                <?php if (!empty($kontak_info)): ?>
                    <div class="contact-grid">
                        <div class="contact-item">
                            <i class="fas fa-clock" style="color: var(--primary-color);"></i>
                            <h3>Jam Pelayanan</h3>
                            <table class="jam-layanan-table">
                                <tr>
                                    <td>Senin - Kamis</td>
                                    <td>: 08:00 - 16:00 WIB</td>
                                </tr>
                                <tr>
                                    <td>Jumat</td>
                                    <td>: 08:00 - 11:30 WIB</td>
                                </tr>
                                <tr>
                                    <td>Sabtu - Minggu</td>
                                    <td>: Libur</td>
                                </tr>
                            </table>
                            <div class="jam-layanan-note">
                                <i class="fas fa-info-circle"></i>
                                <p>Untuk layanan tertentu, silakan hubungi kantor desa terlebih dahulu.</p>
                            </div>
                        </div>

                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt" style="color: var(--primary-color);"></i>
                            <h3>Lokasi Kantor</h3>
                            <p><strong>Alamat:</strong><br>
                            <?php echo nl2br($kontak_info['alamat']); ?></p>
                            <?php if (!empty($kontak_info['link_lokasi'])): ?>
                              <a href="<?php echo htmlspecialchars($kontak_info['link_lokasi']); ?>" target="_blank" class="btn" style="margin-top: 15px; background-color: #27703816; border-color: #23953d28; padding: 10px 20px; font-size: 1em; border-radius: 5px;">
                                 <i class="fas fa-map-marker-alt"></i> Lihat Peta
                                   </a>
                            <?php else: ?>
                              <p style="font-size: 0.9em; color: #888;">Link lokasi tidak tersedia.</p>
                                <?php endif; ?>
                        </div>

                        <div class="contact-item">
                            <i class="fas fa-phone-square-alt" style="color: var(--primary-color);"></i> <h3>Kontak Kami</h3>
                            <p><i class="fas fa-phone"></i> <a href="tel:<?php echo $kontak_info['telepon']; ?>"><?php echo $kontak_info['telepon']; ?></a></p>
                            <p><i class="fas fa-envelope"></i> <a href="mailto:<?php echo $kontak_info['email']; ?>"><?php echo $kontak_info['email']; ?></a></p>
                        </div>


                        <div class="contact-form-section" style="grid-column: 1 / -1;">
                            <h3>Saran / Kritikan</h3>
                            <p style="text-align: center; color: var(--light-text-color); margin-bottom: 30px;">
                                Silakan isi formulir di bawah ini untuk mengirimkan pesan, saran, atau kritikan Anda.
                            </p>
                            <?php if (!empty($message)): ?>
                                <div class="alert <?php echo $message_type; ?>">
                                    <?php echo $message; ?>
                                </div>
                            <?php endif; ?>
                            <form action="kontak.php" method="POST">
                                <div class="application-form-grid">
                                    <div>
                                        <label for="nama">Nama Lengkap <span style="color: red;">*</span></label>
                                        <input type="text" id="nama" name="nama" placeholder="Cth: Budi Santoso" required>
                                    </div>
                                    <div>
                                        <label for="email">Email <span style="color: red;">*</span></label>
                                        <input type="email" id="email" name="email" placeholder="Cth: nama@email.com" required>
                                    </div>
                                    <div>
                                        <label for="no_telepon">No. Telepon</label>
                                        <input type="text" id="no_telepon" name="no_telepon" placeholder="Cth: 081234567890">
                                    </div>
                                    <div>
                                        <label for="subjek">Subjek <span style="color: red;">*</span></label>
                                        <select id="subjek" name="subjek" required>
                                            <option value="">Pilih Subjek</option>
                                            <option value="Saran Umum">Saran Umum</option>
                                            <option value="Kritik">Kritik</option>
                                            <option value="Pertanyaan">Pertanyaan</option>
                                            <option value="Lain-lain">Lain-lain</option>
                                        </select>
                                    </div>
                                    <div class="full-width">
                                        <label for="pesan">Pesan <span style="color: red;">*</span></label>
                                        <textarea id="pesan" name="pesan" rows="6" placeholder="Tuliskan pesan Anda di sini..." required></textarea>
                                    </div>
                                </div>
                                <button type="submit">Kirim Pesan <i class="fas fa-paper-plane"></i></button>
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <p style="text-align: center;">Informasi kontak tidak ditemukan.</p>
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