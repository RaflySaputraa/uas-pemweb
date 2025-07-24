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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_tempat = sanitize_input($_POST['nama_tempat']);
    $deskripsi = sanitize_input($_POST['deskripsi']);
    $gambar_file = $_FILES['gambar'];

    if (empty($nama_tempat) || empty($deskripsi) || empty($gambar_file['name'])) {
        $message = "Semua kolom harus diisi, termasuk gambar.";
        $message_type = "alert-error";
    } elseif (!is_valid_image($gambar_file)) {
        $message = "File gambar tidak valid atau terlalu besar (maks 5MB, format JPG, PNG, GIF).";
        $message_type = "alert-error";
    } else {
        $target_dir = "../public/assets/img/"; // LOKASI BARU UNTUK UPLOAD GAMBAR
        $new_file_name = uniqid() . '_' . basename($gambar_file['name']);
        $target_file = $target_dir . $new_file_name;
        $image_url_db = $new_file_name; // Simpan path tanpa 'public/'

        if (move_uploaded_file($gambar_file['tmp_name'], $target_file)) {
            $stmt = $conn->prepare("INSERT INTO galeri (nama_tempat, deskripsi, gambar_url) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nama_tempat, $deskripsi, $image_url_db);

            if ($stmt->execute()) {
                $message = "Item galeri berhasil ditambahkan!";
                $message_type = "alert-success";
                header('Location: galeri.php?msg=' . urlencode($message) . '&type=' . urlencode($message_type)); // Redirect ke galeri.php
                exit();
            } else {
                $message = "Gagal menambahkan item galeri ke database: " . $stmt->error;
                $message_type = "alert-error";
                unlink($target_file); // Hapus file yang sudah diupload jika gagal disimpan ke DB
            }
            $stmt->close();
        } else {
            $message = "Gagal mengunggah gambar. Pastikan folder ../img/ dapat ditulis (writable).";
            $message_type = "alert-error";
        }
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Galeri</title>
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
                <li><a href="dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="galeri.php"><i class="fas fa-images"></i> Kelola Galeri</a></li>
                <li><a href="profil.php"><i class="fas fa-city"></i> Kelola Profil</a></li>
                <li><a href="layanan.php"><i class="fas fa-briefcase-medical"></i> Kelola Layanan</a></li>
                <li><a href="saran_permohonan.php"><i class="fas fa-clipboard-list"></i> Lihat Saran/Permohonan</a></li>
            </ul>
            <div class="admin-sidebar-footer">
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </aside>

        <main class="admin-main-content">
            <div class="content-header">
                <h1>Tambah Item Galeri</h1>
                <p>Formulir untuk menambahkan item galeri baru.</p>
            </div>

            <div class="admin-card">
                <?php if ($message): ?>
                    <div class="alert <?php echo $message_type; ?>"><i class="fas fa-exclamation-triangle"></i> <?php echo $message; ?></div>
                <?php endif; ?>
                <form class="admin-form" action="galeri_tambah.php" method="POST" enctype="multipart/form-data">
                    <label for="nama_tempat">Nama Tempat:</label>
                    <input type="text" id="nama_tempat" name="nama_tempat" placeholder="Cth: Taman Kota Hijau" required>

                    <label for="deskripsi">Deskripsi:</label>
                    <textarea id="deskripsi" name="deskripsi" rows="5" placeholder="Jelaskan tentang tempat ini..." required></textarea>

                    <label for="gambar">Gambar (JPG, PNG, GIF, maks 5MB):</label>
                    <input type="file" id="gambar" name="gambar" accept="image/jpeg,image/png,image/gif" required>

                    <div class="form-actions">
                        <button type="submit" class="admin-btn btn-success"><i class="fas fa-plus"></i> Tambah Galeri</button>
                        <a href="galeri.php" class="admin-btn btn-secondary"><i class="fas fa-times-circle"></i> Batal</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>