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
$item = []; // Untuk menyimpan data item galeri yang akan diedit
$debug_edit_output = []; // Array untuk debugging proses edit

// Pastikan ada ID yang dikirim melalui GET untuk mode edit
if (isset($_GET['id'])) {
    $id_to_edit = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM galeri WHERE id = ?");
    $stmt->bind_param("i", $id_to_edit);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $item = $result->fetch_assoc();
    } else {
        // Jika item tidak ditemukan, redirect dengan pesan error
        header('Location: galeri.php?msg=' . urlencode("Item galeri tidak ditemukan.") . '&type=' . urlencode("alert-error"));
        exit();
    }
    $stmt->close();
} else {
    // Jika tidak ada ID yang diberikan, redirect kembali ke halaman galeri
    header('Location: galeri.php?msg=' . urlencode("ID galeri tidak disediakan untuk edit.") . '&type=' . urlencode("alert-error"));
    exit();
}


// Handle POST request (saat form di-submit)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $debug_edit_output[] = "POST request received for ID: " . htmlspecialchars($_POST['id']);
    $debug_edit_output[] = "Nama Tempat: " . htmlspecialchars($_POST['nama_tempat']);
    $debug_edit_output[] = "Deskripsi: " . htmlspecialchars($_POST['deskripsi']);
    $debug_edit_output[] = "Gambar Lama URL dari Hidden Field: " . htmlspecialchars($_POST['gambar_lama_url']);
    $debug_edit_output[] = "FILES array status: " . print_r($_FILES, true);


    $id = intval($_POST['id']);
    $nama_tempat = sanitize_input($_POST['nama_tempat']);
    $deskripsi = sanitize_input($_POST['deskripsi']);
    $gambar_lama_url = sanitize_input($_POST['gambar_lama_url']); // Ini adalah path dari DB

    $upload_new_image = false;
    $gambar_url_db = $gambar_lama_url; // Default, akan diubah jika ada upload baru
    $target_file = '';

    // Periksa apakah ada file gambar baru yang diupload
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == UPLOAD_ERR_OK) {
        $gambar_file = $_FILES['gambar'];
        $debug_edit_output[] = "New image file detected: " . htmlspecialchars($gambar_file['name']);

        if (!is_valid_image($gambar_file)) {
            $message = "File gambar baru tidak valid atau terlalu besar (maks 5MB, format JPG, PNG, GIF).";
            $message_type = "alert-error";
            $debug_edit_output[] = "Image validation FAILED.";
            $conn->close();
            goto end_script; // Lompat ke akhir script HTML jika ada error validasi
        } else {
            $upload_new_image = true;
            // TARGET LOKASI FISIK GAMBAR BARU UNTUK UPLOAD (relatif dari folder admin/)
            $target_dir = "../public/assets/img/"; 
            $new_file_name = uniqid() . '_' . basename($gambar_file['name']);
            $target_file = $target_dir . $new_file_name;
            // PATH YANG AKAN DISIMPAN DI DATABASE
            $gambar_url_db = $new_file_name; // Simpan path tanpa 'public/'
            $debug_edit_output[] = "Image valid. New target file path: " . htmlspecialchars($target_file);
            $debug_edit_output[] = "New DB URL to save: " . htmlspecialchars($gambar_url_db);
        }
    } else {
        $debug_edit_output[] = "No new image uploaded or error in upload. Error code: " . ($_FILES['gambar']['error'] ?? 'N/A');
    }

    if (empty($nama_tempat) || empty($deskripsi)) {
        $message = "Nama Tempat dan Deskripsi tidak boleh kosong.";
        $message_type = "alert-error";
        $debug_edit_output[] = "Validation FAILED: Nama/Deskripsi empty.";
    } else {
        if ($upload_new_image) {
            if (move_uploaded_file($gambar_file['tmp_name'], $target_file)) {
                $debug_edit_output[] = "Image moved successfully.";
                // Hapus gambar lama jika ada dan bukan gambar default
                // $gambar_lama_url diambil dari database, yang seharusnya sudah 'public/assets/img/...'
                $old_image_path_full = '../public/assets/img/' . $gambar_lama_url;
                
                if (!empty($gambar_lama_url) && file_exists($old_image_path_full) && !is_dir($old_image_path_full) &&
                    !strpos($gambar_lama_url, 'default.jpg') && !strpos($gambar_lama_url, 'placeholder.png')) {
                    unlink($old_image_path_full);
                    $debug_edit_output[] = "Old image deleted: " . htmlspecialchars($old_image_path_full);
                } else {
                    $debug_edit_output[] = "Old image NOT deleted (not found or default/empty): " . htmlspecialchars($old_image_path_full);
                }
            } else {
                $message = "Gagal mengunggah gambar baru. Pastikan folder ../public/assets/img/ dapat ditulis.";
                $message_type = "alert-error";
                $debug_edit_output[] = "Failed to move uploaded file. Check folder permissions for ../public/assets/img/.";
                $conn->close();
                goto end_script;
            }
        }

        // Query UPDATE data galeri di database
        $stmt = $conn->prepare("UPDATE galeri SET nama_tempat = ?, deskripsi = ?, gambar_url = ? WHERE id = ?");
        if ($stmt === false) {
            $debug_edit_output[] = "SQL Prepare FAILED: " . $conn->error;
            $message = "Terjadi kesalahan sistem saat menyiapkan update database.";
            $message_type = "alert-error";
            $conn->close();
            goto end_script;
        }
        $stmt->bind_param("sssi", $nama_tempat, $deskripsi, $gambar_url_db, $id);

        if ($stmt->execute()) {
            $affected_rows = $stmt->affected_rows;
            $debug_edit_output[] = "SQL Execute SUCCESS. Affected Rows: " . $affected_rows;
            if ($affected_rows > 0) {
                $message = "Item galeri berhasil diperbarui!";
                $message_type = "alert-success";
            } else {
                $message = "Tidak ada perubahan yang terdeteksi untuk item galeri ini."; // Jika data sama persis
                $message_type = "alert-info"; 
            }
            $stmt->close();
            $conn->close();
            // Setelah sukses, redirect kembali ke halaman daftar galeri dengan pesan
            header('Location: galeri.php?msg=' . urlencode($message) . '&type=' . urlencode($message_type));
            exit();
        } else {
            $debug_edit_output[] = "SQL Execute FAILED: " . $stmt->error;
            $message = "Gagal memperbarui item galeri: " . $stmt->error;
            $message_type = "alert-error";
            // Jika update DB gagal setelah upload, hapus gambar baru yang sudah diupload
            if ($upload_new_image && file_exists($target_file)) {
                unlink($target_file);
                $debug_edit_output[] = "Uploaded new image deleted due to DB update failure.";
            }
        }
        $stmt->close();
    }
}
end_script:
// Pastikan koneksi ditutup hanya jika itu objek mysqli yang valid dan belum ditutup
if (isset($conn) && $conn instanceof mysqli && $conn->ping()) {
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Galeri</title>
    <link rel="stylesheet" href="css/admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
                <h1>Edit Item Galeri</h1>
                <p>Formulir untuk mengubah detail item galeri.</p>
            </div>

            <div class="admin-card">
                <?php if ($message): ?>
                    <div class="alert <?php echo $message_type; ?>"><i class="fas fa-exclamation-triangle"></i> <?php echo $message; ?></div>
                <?php endif; ?>

                <?php if (!empty($item)): ?>
                    <form class="admin-form" action="galeri_edit.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($item['id']); ?>">
                        <input type="hidden" name="gambar_lama_url" value="<?php echo htmlspecialchars($item['gambar_url']); ?>">

                        <label for="nama_tempat">Nama Tempat:</label>
                        <input type="text" id="nama_tempat" name="nama_tempat" value="<?php echo htmlspecialchars($item['nama_tempat']); ?>" required>

                        <label for="deskripsi">Deskripsi:</label>
                        <textarea id="deskripsi" name="deskripsi" rows="5" required><?php echo htmlspecialchars($item['deskripsi']); ?></textarea>

                        <label>Gambar Saat Ini:</label>
                        <img src="../<?php echo htmlspecialchars($item['gambar_url']); ?>" 
                             alt="<?php echo htmlspecialchars($item['nama_tempat']); ?>" 
                             style="max-width: 200px; display: block; margin-bottom: 20px;">

                        <label for="gambar">Ganti Gambar (opsional, JPG, PNG, GIF, maks 5MB):</label>
                        <input type="file" id="gambar" name="gambar" accept="image/jpeg,image/png,image/gif">

                        <div class="form-actions">
                            <button type="submit" class="admin-btn"><i class="fas fa-save"></i> Update Galeri</button>
                            <a href="galeri.php" class="admin-btn btn-secondary"><i class="fas fa-times-circle"></i> Batal</a>
                        </div>
                    </form>
                <?php else: ?>
                    <p style="text-align: center; color: var(--text-medium);">Item galeri yang ingin Anda edit tidak ditemukan.</p>
                    <a href="galeri.php" class="admin-btn btn-secondary"><i class="fas fa-arrow-alt-circle-left"></i> Kembali ke Galeri</a>
                <?php endif; ?>
            </div>

            <?php if (!empty($debug_edit_output)): ?>
                <div style="margin-top: 30px; padding: 15px; background-color: #e6f7ff; border: 1px solid #91d5ff; border-radius: 8px; text-align: left; font-size: 0.9em; color: #096dd9;">
                    <strong>Debug Edit Info:</strong>
                    <ul>
                        <?php foreach ($debug_edit_output as $line): ?>
                            <li><?php echo nl2br(htmlspecialchars($line)); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>