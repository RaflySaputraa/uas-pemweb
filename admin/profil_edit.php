<?php
session_start();
include '../includes/db_config.php';
include '../includes/functions.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit();
}

$message = '';
$message_type = '';
$profil_kota = []; // Data profil kota yang akan diedit

// Ambil data profil kota untuk ditampilkan di form
$sql = "SELECT * FROM profil_kota LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $profil_kota = $result->fetch_assoc();
} else {
    $message = "Data profil kota tidak ditemukan untuk diedit. Mohon tambahkan data.";
    $message_type = "alert-error";
    $profil_kota = null; // Tandai tidak ada data
}


// Handle POST request (saat form di-submit)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST)) {
    // Ambil dan sanitasi semua input
    $nama_kota = sanitize_input($_POST['nama_kota']);
    $provinsi = sanitize_input($_POST['provinsi']);
    $kode_pos = sanitize_input($_POST['kode_pos']);
    $luas_wilayah = sanitize_input($_POST['luas_wilayah']);
    $jumlah_penduduk = sanitize_input($_POST['jumlah_penduduk']);
    $visi = sanitize_input($_POST['visi']);
    $misi = sanitize_input($_POST['misi']);
    $sejarah = sanitize_input($_POST['sejarah']);
    $total_penduduk = sanitize_input($_POST['total_penduduk']);
    $luas_wilayah_statistik = sanitize_input($_POST['luas_wilayah_statistik']);
    $kepala_keluarga = sanitize_input($_POST['kepala_keluarga']);
    $lahan_pertanian = sanitize_input($_POST['lahan_pertanian']);

    // Validasi dasar
    if (empty($nama_kota) || empty($provinsi) || empty($kode_pos) || empty($luas_wilayah) || empty($jumlah_penduduk)) {
        $message = "Nama Kota, Provinsi, Kode Pos, Luas Wilayah, dan Jumlah Penduduk tidak boleh kosong.";
        $message_type = "alert-error";
    } else {
        // Query UPDATE. Asumsi hanya ada 1 baris dengan ID 1.
        $stmt = $conn->prepare("UPDATE profil_kota SET 
                                nama_kota = ?, provinsi = ?, kode_pos = ?, luas_wilayah = ?, jumlah_penduduk = ?,
                                visi = ?, misi = ?, sejarah = ?, total_penduduk = ?, luas_wilayah_statistik = ?,
                                kepala_keluarga = ?, lahan_pertanian = ?
                                WHERE id = 1"); // Asumsi ID profil selalu 1
        
        $stmt->bind_param("ssssssssssss", 
                            $nama_kota, $provinsi, $kode_pos, $luas_wilayah, $jumlah_penduduk,
                            $visi, $misi, $sejarah, $total_penduduk, $luas_wilayah_statistik,
                            $kepala_keluarga, $lahan_pertanian);

        if ($stmt->execute()) {
            $affected_rows = $stmt->affected_rows;
            if ($affected_rows > 0) {
                $message = "Profil kota berhasil diperbarui!";
                $message_type = "alert-success";
            } else {
                $message = "Tidak ada perubahan yang terdeteksi pada profil kota.";
                $message_type = "alert-info";
            }
            $stmt->close();
            // Redirect kembali ke halaman profil.php dengan pesan
            header('Location: profil.php?msg=' . urlencode($message) . '&type=' . urlencode($message_type));
            exit();
        } else {
            $message = "Gagal memperbarui profil kota: " . $stmt->error;
            $message_type = "alert-error";
        }
        $stmt->close();
    }
    // Jika ada error POST, data profil_kota perlu dimuat ulang untuk form
    $sql = "SELECT * FROM profil_kota LIMIT 1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $profil_kota = $result->fetch_assoc();
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil Kota</title>
    <link rel="stylesheet" href="css/admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="admin-body">
    <div class="admin-wrapper">
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <h2>Admin Panel</h2>
                <p>Kota Purwokerto</p>
            </div>
            <ul>
                <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="galeri.php"><i class="fas fa-images"></i> Kelola Galeri</a></li>
                <li><a href="profil.php" class="active"><i class="fas fa-city"></i> Kelola Profil</a></li> <li><a href="layanan.php"><i class="fas fa-briefcase-medical"></i> Kelola Layanan</a></li>
                <li><a href="saran_permohonan.php"><i class="fas fa-clipboard-list"></i> Lihat Saran/Permohonan</a></li>
            </ul>
            <div class="admin-sidebar-footer">
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </aside>

        <main class="admin-main-content">
            <div class="content-header">
                <h1>Edit Profil Kota</h1>
                <p>Perbarui informasi profil, visi, misi, sejarah, dan statistik kota.</p>
            </div>

            <div class="admin-card">
                <?php if ($message): ?>
                    <div class="alert <?php echo $message_type; ?>"><i class="fas fa-exclamation-triangle"></i> <?php echo $message; ?></div>
                <?php endif; ?>

                <?php if (!empty($profil_kota)): ?>
                    <form class="admin-form" action="profil_edit.php" method="POST">
                        <h3>Informasi Umum</h3>
                        <label for="nama_kota">Nama Kota:</label>
                        <input type="text" id="nama_kota" name="nama_kota" value="<?php echo htmlspecialchars($profil_kota['nama_kota']); ?>" required>

                        <label for="provinsi">Provinsi:</label>
                        <input type="text" id="provinsi" name="provinsi" value="<?php echo htmlspecialchars($profil_kota['provinsi']); ?>" required>

                        <label for="kode_pos">Kode Pos:</label>
                        <input type="text" id="kode_pos" name="kode_pos" value="<?php echo htmlspecialchars($profil_kota['kode_pos']); ?>" required>

                        <label for="luas_wilayah">Luas Wilayah (Umum):</label>
                        <input type="text" id="luas_wilayah" name="luas_wilayah" value="<?php echo htmlspecialchars($profil_kota['luas_wilayah']); ?>" required>

                        <label for="jumlah_penduduk">Jumlah Penduduk (Umum):</label>
                        <input type="text" id="jumlah_penduduk" name="jumlah_penduduk" value="<?php echo htmlspecialchars($profil_kota['jumlah_penduduk']); ?>" required>

                        <h3 style="margin-top: 30px;">Visi & Misi</h3>
                        <label for="visi">Visi:</label>
                        <textarea id="visi" name="visi" rows="5" required><?php echo htmlspecialchars($profil_kota['visi']); ?></textarea>

                        <label for="misi">Misi:</label>
                        <textarea id="misi" name="misi" rows="8" required><?php echo htmlspecialchars($profil_kota['misi']); ?></textarea>

                        <h3 style="margin-top: 30px;">Sejarah</h3>
                        <label for="sejarah">Sejarah:</label>
                        <textarea id="sejarah" name="sejarah" rows="10" required><?php echo htmlspecialchars($profil_kota['sejarah']); ?></textarea>

                        <h3 style="margin-top: 30px;">Statistik Detail</h3>
                        <label for="total_penduduk">Total Penduduk (Statistik):</label>
                        <input type="text" id="total_penduduk" name="total_penduduk" value="<?php echo htmlspecialchars($profil_kota['total_penduduk']); ?>">

                        <label for="luas_wilayah_statistik">Luas Wilayah (Statistik):</label>
                        <input type="text" id="luas_wilayah_statistik" name="luas_wilayah_statistik" value="<?php echo htmlspecialchars($profil_kota['luas_wilayah_statistik']); ?>">

                        <label for="kepala_keluarga">Kepala Keluarga:</label>
                        <input type="text" id="kepala_keluarga" name="kepala_keluarga" value="<?php echo htmlspecialchars($profil_kota['kepala_keluarga']); ?>">

                        <label for="lahan_pertanian">Lahan Pertanian:</label>
                        <input type="text" id="lahan_pertanian" name="lahan_pertanian" value="<?php echo htmlspecialchars($profil_kota['lahan_pertanian']); ?>">
                        
                        <div class="form-actions">
                            <button type="submit" class="admin-btn"><i class="fas fa-save"></i> Update Profil</button>
                            <a href="profil.php" class="admin-btn btn-secondary"><i class="fas fa-times-circle"></i> Batal</a>
                        </div>
                    </form>
                <?php else: ?>
                    <p style="text-align: center; color: var(--text-medium);">Data profil kota tidak ditemukan di database. Mohon masukkan data awal melalui phpMyAdmin jika belum ada.</p>
                    <div class="form-actions" style="justify-content: center;">
                        <a href="profil.php" class="admin-btn btn-secondary"><i class="fas fa-arrow-alt-circle-left"></i> Kembali ke Profil</a>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>