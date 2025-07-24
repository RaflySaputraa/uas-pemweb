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

    // Pertama, ambil nama file gambar untuk dihapus dari server
    $stmt_get_image = $conn->prepare("SELECT gambar_url FROM galeri WHERE id = ?");
    $stmt_get_image->bind_param("i", $id_to_delete);
    $stmt_get_image->execute();
    $result_image = $stmt_get_image->get_result();
    if ($result_image->num_rows > 0) {
        $row_image = $result_image->fetch_assoc();
   
        $image_path = '../' . $row_image['gambar_url']; 

        // Hapus entri dari database
        $stmt_delete = $conn->prepare("DELETE FROM galeri WHERE id = ?");
        $stmt_delete->bind_param("i", $id_to_delete);
        if ($stmt_delete->execute()) {

            if (!empty($row_image['gambar_url']) && file_exists($image_path) && !is_dir($image_path) && 
                !strpos($row_image['gambar_url'], 'default.jpg') && !strpos($row_image['gambar_url'], 'placeholder.png')) {
                unlink($image_path);
            }
            $message = "Item galeri berhasil dihapus!";
            $message_type = "alert-success";
        } else {
            $message = "Gagal menghapus item galeri: " . $stmt_delete->error;
            $message_type = "alert-error";
        }
        $stmt_delete->close();
    } else {
        $message = "Item galeri tidak ditemukan.";
        $message_type = "alert-error";
    }
    $stmt_get_image->close();
    header('Location: galeri.php?msg=' . urlencode($message) . '&type=' . urlencode($message_type)); // Redirect untuk pesan
    exit();
}

// Tampilkan pesan jika ada dari redirect sebelumnya (setelah tambah/edit/hapus)
if (isset($_GET['msg']) && isset($_GET['type'])) {
    $message = htmlspecialchars($_GET['msg']);
    $message_type = htmlspecialchars($_GET['type']);
}

// Ambil semua data galeri untuk ditampilkan
$galeri_list = [];
$sql = "SELECT * FROM galeri ORDER BY id DESC";
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
    <title>Kelola Galeri</title>
    <link rel="stylesheet" href="css/admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script>
        function confirmDelete(id) {
            if (confirm("Apakah Anda yakin ingin menghapus item galeri ini? Ini tidak dapat dibatalkan.")) {
                window.location.href = 'galeri.php?action=delete&id=' + id;
            }
        }
    </script>
</head>
<body class="admin-body">
    <div class="admin-wrapper">
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <h2>Admin Panel</h2>
                <p>Kota Anda</p>
            </div>
            <ul>
                <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="galeri.php" class="active"><i class="fas fa-images"></i> Kelola Galeri</a></li>
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
                <h1>Kelola Galeri</h1>
                <p>Tambah, edit, atau hapus item galeri website.</p>
            </div>

            <div class="admin-card">
                <?php if ($message): ?>
                    <div class="alert <?php echo $message_type; ?>"><i class="fas fa-info-circle"></i> <?php echo $message; ?></div>
                <?php endif; ?>

                <a href="galeri_tambah.php" class="admin-btn btn-success"><i class="fas fa-plus-circle"></i> Tambah Item Galeri Baru</a>

                <?php if (!empty($galeri_list)): ?>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Gambar</th>
                                <th>Nama Tempat</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($galeri_list as $item): ?>
                                <tr>
                                    <td data-label="ID"><?php echo $item['id']; ?></td>
                                    <td data-label="Gambar">
                                        <img src="../public/assets/img/<?php echo htmlspecialchars($item['gambar_url']); ?>" 
                                            alt="<?php echo htmlspecialchars($item['nama_tempat']); ?>"
                                             onerror="this.onerror=null;this.src='../public/assets/img/placeholder.png';">
                                    </td>
                                    <td data-label="Nama Tempat"><?php echo htmlspecialchars($item['nama_tempat']); ?></td>
                                    <td data-label="Deskripsi"><?php echo htmlspecialchars(substr($item['deskripsi'], 0, 100)) . (strlen($item['deskripsi']) > 100 ? '...' : ''); ?></td>
                                    <td data-label="Aksi" class="action-buttons">
                                        <a href="galeri_edit.php?id=<?php echo $item['id']; ?>" class="admin-btn btn-secondary"><i class="fas fa-edit"></i> Edit</a>
                                        <a href="#" onclick="confirmDelete(<?php echo $item['id']; ?>)" class="admin-btn btn-danger"><i class="fas fa-trash-alt"></i> Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p style="text-align: center; margin-top: 20px; color: var(--text-medium);">Belum ada item galeri. Klik "Tambah Item Galeri Baru" untuk memulai.</p>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>