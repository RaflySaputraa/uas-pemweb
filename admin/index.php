<?php
// Tampilkan semua error untuk debugging - HANYA UNTUK PENGEMBANGAN!
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Mulai sesi PHP
session_start();

// Sertakan file konfigurasi database
include '../includes/db_config.php';

$message = ''; // Pesan feedback untuk pengguna
$debug_output = []; // Untuk debugging internal (bisa dihapus di produksi)

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username_input = $_POST['username'];
    $password_input = $_POST['password'];

    $debug_output[] = "Attempting login for username: '" . htmlspecialchars($username_input) . "'";

    // Siapkan query untuk mengambil user
    $stmt = $conn->prepare("SELECT id, username, password FROM admin_users WHERE username = ?");
    if ($stmt === false) {
        $debug_output[] = "ERROR: Gagal menyiapkan statement SQL - " . $conn->error;
        $message = "Terjadi kesalahan sistem, coba lagi.";
        goto end_process;
    }

    $stmt->bind_param("s", $username_input);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $stored_hash = $user['password'];

        $debug_output[] = "User found in DB. Stored Hash: '" . htmlspecialchars($stored_hash) . "'";
        $debug_output[] = "Password Input Length: " . strlen($password_input);
        $debug_output[] = "Stored Hash Length: " . strlen($stored_hash);

        // Verifikasi password
        if (password_verify($password_input, $stored_hash)) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $user['username'];
            $debug_output[] = "Password verification SUCCESS. Redirecting...";
            header('Location: dashboard.php'); // Redirect ke dashboard
            exit();
        } else {
            $message = "Username atau password salah.";
            $debug_output[] = "Password verification FAILED.";
        }
    } else {
        $message = "Username atau password salah.";
        $debug_output[] = "User not found or multiple users found.";
    }
    $stmt->close();
}

end_process:
if (isset($conn) && $conn instanceof mysqli) { // Pastikan $conn ada dan objek mysqli
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="css/admin.css"> <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="admin-body"> <div class="login-container">
        <h1>Login Admin</h1>
        <p>Masukkan username dan password Anda</p>
        <form class="admin-form" action="index.php" method="POST">
            <?php if ($message): ?>
                <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?php echo $message; ?></div>
            <?php endif; ?>
            
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Masukkan username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Masukkan password" required>

            <button type="submit" class="admin-btn"><i class="fas fa-sign-in-alt"></i> Login</button>
        </form>

        <?php if (!empty($debug_output)): ?>
            <div style="margin-top: 30px; padding: 15px; background-color: #e6f7ff; border: 1px solid #91d5ff; border-radius: 8px; text-align: left; font-size: 0.9em; color: #096dd9;">
                <strong>Debug Info:</strong>
                <ul>
                    <?php foreach ($debug_output as $line): ?>
                        <li><?php echo $line; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>