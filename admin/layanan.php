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

// Handle Delete request
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id_to_delete = intval($_GET['id']);

    $stmt_delete = $conn->prepare("DELETE FROM layanan WHERE id = ?");
    $stmt_delete->bind_param("i", $id_to_delete);
    if ($stmt_delete->execute()) {
        $message = "Layanan berhasil dihapus!";
        $message_type = "alert-success";
    } else {
        $message = "Gagal menghapus layanan: " . $stmt_delete->error;
        $message_type = "alert-error";
    }
    $stmt_delete->close();
    header('Location: layanan.php?msg=' . urlencode($message) . '&type=' . urlencode($message_type));
    exit();
}

// Tampilkan pesan jika ada dari redirect sebelumnya (setelah tambah/edit/hapus)
if (isset($_GET['msg']) && isset($_GET['type'])) {
    $message = htmlspecialchars($_GET['msg']);
    $message_type = htmlspecialchars($_GET['type']);
}

// Ambil semua data layanan untuk ditampilkan
$layanan_list = [];
$sql = "SELECT * FROM layanan ORDER BY nama_layanan ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $layanan_list[] = $row;
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Layanan</title>
    <link rel="stylesheet" href="css/admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script>
        function confirmDelete(id) {
            if (confirm("Apakah Anda yakin ingin menghapus layanan ini?")) {
                window.location.href = 'layanan.php?action=delete&id=' + id;
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
                <li><a href="layanan.php" class="active"><i class="fas fa-briefcase-medical"></i> Kelola Layanan</a></li>
                <li><a href="saran_permohonan.php"><i class="fas fa-clipboard-list"></i> Lihat Saran/Permohonan</a></li>
            </ul>
            <div class="admin-sidebar-footer">
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </aside>

        <main class="admin-main-content">
            <div class="content-header">
                <h1>Kelola Layanan</h1>
                <p>Tambah, edit, atau hapus layanan publik.</p>
            </div>

            <div class="admin-card">
                <?php if ($message): ?>
                    <div class="alert <?php echo $message_type; ?>"><i class="fas fa-info-circle"></i> <?php echo $message; ?></div>
                <?php endif; ?>

                <a href="layanan_tambah.php" class="admin-btn btn-success"><i class="fas fa-plus-circle"></i> Tambah Layanan Baru</a>

                <?php if (!empty($layanan_list)): ?>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Layanan</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($layanan_list as $layanan): ?>
                                <tr>
                                    <td data-label="ID"><?php echo $layanan['id']; ?></td>
                                    <td data-label="Nama Layanan"><?php echo htmlspecialchars($layanan['nama_layanan']); ?></td>
                                    <td data-label="Deskripsi"><?php echo htmlspecialchars(substr($layanan['deskripsi'], 0, 150)) . (strlen($layanan['deskripsi']) > 150 ? '...' : ''); ?></td>
                                    <td data-label="Aksi" class="action-buttons">
                                        <a href="layanan_edit.php?id=<?php echo $layanan['id']; ?>" class="admin-btn btn-secondary"><i class="fas fa-edit"></i> Edit</a>
                                        <a href="#" onclick="confirmDelete(<?php echo $layanan['id']; ?>)" class="admin-btn btn-danger"><i class="fas fa-trash-alt"></i> Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p style="text-align: center; margin-top: 20px; color: var(--text-medium);">Belum ada layanan yang tersedia. Tambahkan yang pertama!</p>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>