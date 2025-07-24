<?php
// Fungsi untuk membersihkan input data
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Fungsi untuk memvalidasi file gambar yang diupload
function is_valid_image($file) {
    $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed_mime_types = array('image/jpeg', 'image/png', 'image/gif');

    if (!in_array($file_extension, $allowed_extensions)) {
        return false;
    }
    if (!in_array($file['type'], $allowed_mime_types)) {
        return false;
    }
    if ($file['size'] > 5 * 1024 * 1024) { // Max 5MB
        return false;
    }
    $image_info = getimagesize($file['tmp_name']); // Verifikasi file asli sebagai gambar
    if ($image_info === false) {
        return false;
    }
    return true;
}
?>