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
    $nama_layanan = sanitize_input($_POST['nama_layanan']);
    $deskripsi = sanitize_input($_POST['deskripsi']);

    if (empty($nama_layanan) || empty($deskripsi)) {
        $message = "Nama Layanan dan Deskripsi tidak boleh kosong.";
        $message_type = "alert-error";
    } else {
        $stmt = $conn->prepare("INSERT INTO layanan (nama_layanan, deskripsi) VALUES (?, ?)");
        $stmt->bind_param("ss", $nama_layanan, $deskripsi);

        if ($stmt->execute()) {
            $message = "Layanan berhasil ditambahkan!";
            $message_type = "alert-success";
            header('Location: layanan.php?msg=' . urlencode($message) . '&type=' . urlencode($message_type));
            exit();
        } else {
            $message = "Gagal menambahkan layanan: " . $stmt->error;
            $message_type = "alert-error";
        }
        $stmt->close();
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Layanan</title>
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
                <li><a href="profil.php"><i class="fas fa-city"></i> Kelola Profil</a></li>
                <li><a href="layanan.php" class="active"><i class="fas fa-briefcase-medical"></i> Kelola Layanan</a></li>
                <li><a href="saran_permohonan.php"><i class="fas fa-clipboard-list"></i> Lihat Saran/Permohonan</a></li>
            </ul>
            <div class="admin-sidebar-footer">
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </aside>

        <main class="admin-main-content">
            <div class="content-header">
                <h1>Tambah Layanan Baru</h1>
                <p>Formulir untuk menambahkan layanan publik baru.</p>
            </div>

            <div class="admin-card">
                <?php if ($message): ?>
                    <div class="alert <?php echo $message_type; ?>"><i class="fas fa-exclamation-triangle"></i> <?php echo $message; ?></div>
                <?php endif; ?>
                <form class="admin-form" action="layanan_tambah.php" method="POST">
                    <label for="nama_layanan">Nama Layanan:</label>
                    <input type="text" id="nama_layanan" name="nama_layanan" placeholder="Cth: Kartu Keluarga" required>

                    <label for="deskripsi">Deskripsi:</label>
                    <textarea id="deskripsi" name="deskripsi" rows="5" placeholder="Jelaskan detail layanan ini..." required></textarea>

                    <div class="form-actions">
                        <button type="submit" class="admin-btn btn-success"><i class="fas fa-plus"></i> Tambah Layanan</button>
                        <a href="layanan.php" class="admin-btn btn-secondary"><i class="fas fa-times-circle"></i> Batal</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>