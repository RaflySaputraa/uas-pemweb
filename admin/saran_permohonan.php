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

// Handle Delete request for Saran/Kritikan
if (isset($_GET['action']) && $_GET['action'] == 'delete_saran' && isset($_GET['id'])) {
    $id_to_delete = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM saran_kritikan WHERE id = ?");
    $stmt->bind_param("i", $id_to_delete);
    if ($stmt->execute()) {
        $message = "Saran/Kritikan berhasil dihapus!";
        $message_type = "alert-success";
    } else {
        $message = "Gagal menghapus Saran/Kritikan: " . $stmt->error;
        $message_type = "alert-error";
    }
    $stmt->close();
    header('Location: saran_permohonan.php?msg=' . urlencode($message) . '&type=' . urlencode($message_type));
    exit();
}

// Handle Delete request for Permohonan Layanan
if (isset($_GET['action']) && $_GET['action'] == 'delete_permohonan' && isset($_GET['id'])) {
    $id_to_delete = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM permohonan_layanan WHERE id = ?");
    $stmt->bind_param("i", $id_to_delete);
    if ($stmt->execute()) {
        $message = "Permohonan Layanan berhasil dihapus!";
        $message_type = "alert-success";
    } else {
        $message = "Gagal menghapus Permohonan Layanan: " . $stmt->error;
        $message_type = "alert-error";
    }
    $stmt->close();
    header('Location: saran_permohonan.php?msg=' . urlencode($message) . '&type=' . urlencode($message_type));
    exit();
}


// Tampilkan pesan jika ada dari redirect sebelumnya
if (isset($_GET['msg']) && isset($_GET['type'])) {
    $message = htmlspecialchars($_GET['msg']);
    $message_type = htmlspecialchars($_GET['type']);
}

// Ambil data Saran/Kritikan
$saran_list = [];
$sql_saran = "SELECT * FROM saran_kritikan ORDER BY tanggal DESC";
$result_saran = $conn->query($sql_saran);
if ($result_saran->num_rows > 0) {
    while($row = $result_saran->fetch_assoc()) {
        $saran_list[] = $row;
    }
}

// Ambil data Permohonan Layanan
$permohonan_list = [];
$sql_permohonan = "SELECT * FROM permohonan_layanan ORDER BY tanggal_permohonan DESC";
$result_permohonan = $conn->query($sql_permohonan);
if ($result_permohonan->num_rows > 0) {
    while($row = $result_permohonan->fetch_assoc()) {
        $permohonan_list[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Saran & Permohonan</title>
    <link rel="stylesheet" href="css/admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script>
        function confirmDeleteSaran(id) {
            if (confirm("Apakah Anda yakin ingin menghapus saran/kritikan ini?")) {
                window.location.href = 'saran_permohonan.php?action=delete_saran&id=' + id;
            }
        }
        function confirmDeletePermohonan(id) {
            if (confirm("Apakah Anda yakin ingin menghapus permohonan layanan ini?")) {
                window.location.href = 'saran_permohonan.php?action=delete_permohonan&id=' + id;
            }
        }
    </script>
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
                <li><a href="layanan.php"><i class="fas fa-briefcase-medical"></i> Kelola Layanan</a></li>
                <li><a href="saran_permohonan.php" class="active"><i class="fas fa-clipboard-list"></i> Lihat Saran/Permohonan</a></li>
            </ul>
            <div class="admin-sidebar-footer">
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </aside>

        <main class="admin-main-content">
            <div class="content-header">
                <h1>Lihat Saran & Permohonan</h1>
                <p>Pantau semua saran, kritikan, dan permohonan layanan dari masyarakat.</p>
            </div>

            <div class="admin-card">
                <?php if ($message): ?>
                    <div class="alert <?php echo $message_type; ?>"><i class="fas fa-info-circle"></i> <?php echo $message; ?></div>
                <?php endif; ?>

                <h2>Saran & Kritikan</h2>
                <?php if (!empty($saran_list)): ?>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Telepon</th>
                                <th>Subjek</th>
                                <th>Pesan</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($saran_list as $saran): ?>
                                <tr>
                                    <td data-label="ID"><?php echo $saran['id']; ?></td>
                                    <td data-label="Nama"><?php echo htmlspecialchars($saran['nama']); ?></td>
                                    <td data-label="Email"><?php echo htmlspecialchars($saran['email']); ?></td>
                                    <td data-label="Telepon"><?php echo htmlspecialchars($saran['no_telepon']); ?></td>
                                    <td data-label="Subjek"><?php echo htmlspecialchars($saran['subjek']); ?></td>
                                    <td data-label="Pesan"><?php echo htmlspecialchars(substr($saran['pesan'], 0, 100)) . (strlen($saran['pesan']) > 100 ? '...' : ''); ?></td>
                                    <td data-label="Tanggal"><?php echo htmlspecialchars($saran['tanggal']); ?></td>
                                    <td data-label="Aksi" class="action-buttons">
                                        <a href="#" onclick="confirmDeleteSaran(<?php echo $saran['id']; ?>)" class="admin-btn btn-danger"><i class="fas fa-trash-alt"></i> Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p style="text-align: center; margin-top: 10px; color: var(--text-medium);">Tidak ada saran atau kritikan.</p>
                <?php endif; ?>

                <h2 style="margin-top: 50px;">Permohonan Layanan</h2>
                <?php if (!empty($permohonan_list)): ?>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th>Telepon</th>
                                <th>Jenis Layanan</th>
                                <th>Keterangan</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($permohonan_list as $permohonan): ?>
                                <tr>
                                    <td data-label="ID"><?php echo $permohonan['id']; ?></td>
                                    <td data-label="Nama Lengkap"><?php echo htmlspecialchars($permohonan['nama_lengkap']); ?></td>
                                    <td data-label="Email"><?php echo htmlspecialchars($permohonan['email']); ?></td>
                                    <td data-label="Telepon"><?php echo htmlspecialchars($permohonan['no_telepon']); ?></td>
                                    <td data-label="Jenis Layanan"><?php echo htmlspecialchars($permohonan['jenis_layanan']); ?></td>
                                    <td data-label="Keterangan"><?php echo htmlspecialchars(substr($permohonan['keterangan_tambahan'], 0, 100)) . (strlen($permohonan['keterangan_tambahan']) > 100 ? '...' : ''); ?></td>
                                    <td data-label="Tanggal"><?php echo htmlspecialchars($permohonan['tanggal_permohonan']); ?></td>
                                    <td data-label="Aksi" class="action-buttons">
                                        <a href="#" onclick="confirmDeletePermohonan(<?php echo $permohonan['id']; ?>)" class="admin-btn btn-danger"><i class="fas fa-trash-alt"></i> Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p style="text-align: center; margin-top: 10px; color: var(--text-medium);">Tidak ada permohonan layanan.</p>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>