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

// Tampilkan pesan jika ada dari redirect sebelumnya (setelah edit)
if (isset($_GET['msg']) && isset($_GET['type'])) {
    $message = htmlspecialchars($_GET['msg']);
    $message_type = htmlspecialchars($_GET['type']);
}

$profil_kota = [];
$sql = "SELECT * FROM profil_kota LIMIT 1"; // Asumsi hanya ada 1 baris data profil kota
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $profil_kota = $result->fetch_assoc();
} else {
    $message = "Data profil kota tidak ditemukan. Mohon tambahkan data.";
    $message_type = "alert-info";
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Profil Kota</title>
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
                <h1>Kelola Profil Kota</h1>
                <p>Lihat dan perbarui informasi profil, visi, misi, sejarah, dan statistik kota.</p>
            </div>

            <div class="admin-card">
                <?php if ($message): ?>
                    <div class="alert <?php echo $message_type; ?>"><i class="fas fa-info-circle"></i> <?php echo $message; ?></div>
                <?php endif; ?>

                <?php if (!empty($profil_kota)): ?>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th colspan="2">Detail Profil Kota</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Nama Kota</strong></td>
                                <td><?php echo htmlspecialchars($profil_kota['nama_kota']); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Provinsi</strong></td>
                                <td><?php echo htmlspecialchars($profil_kota['provinsi']); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Kode Pos</strong></td>
                                <td><?php echo htmlspecialchars($profil_kota['kode_pos']); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Luas Wilayah (Umum)</strong></td>
                                <td><?php echo htmlspecialchars($profil_kota['luas_wilayah']); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Jumlah Penduduk (Umum)</strong></td>
                                <td><?php echo htmlspecialchars($profil_kota['jumlah_penduduk']); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Visi</strong></td>
                                <td><?php echo nl2br(htmlspecialchars($profil_kota['visi'])); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Misi</strong></td>
                                <td><?php echo nl2br(htmlspecialchars($profil_kota['misi'])); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Sejarah</strong></td>
                                <td><?php echo nl2br(htmlspecialchars($profil_kota['sejarah'])); ?></td>
                            </tr>
                        </tbody>
                    </table>

                    <h3 style="margin-top: 40px; text-align: center;">Statistik Kota</h3>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th colspan="2">Detail Statistik</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Total Penduduk (Statistik)</strong></td>
                                <td><?php echo htmlspecialchars($profil_kota['total_penduduk']); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Luas Wilayah (Statistik)</strong></td>
                                <td><?php echo htmlspecialchars($profil_kota['luas_wilayah_statistik']); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Kepala Keluarga</strong></td>
                                <td><?php echo htmlspecialchars($profil_kota['kepala_keluarga']); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Lahan Pertanian</strong></td>
                                <td><?php echo htmlspecialchars($profil_kota['lahan_pertanian']); ?></td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="form-actions" style="justify-content: center; margin-top: 30px;">
                        <a href="profil_edit.php" class="admin-btn btn-secondary"><i class="fas fa-edit"></i> Edit Profil</a>
                    </div>

                <?php else: ?>
                    <p style="text-align: center; color: var(--text-medium);">Data profil kota belum tersedia. Silakan tambahkan data awal melalui phpMyAdmin jika belum ada.</p>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>