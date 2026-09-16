<?php
session_start();
require '../config/config.php';

// Inisialisasi hitungan percobaan jika belum ada
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

if (isset($_POST['login'])) {
    // Jika sudah 3x salah, langsung stop proses (keamanan ekstra di backend)
    if ($_SESSION['login_attempts'] >= 3) {
        $error = "Akses ditolak! Form login telah dinonaktifkan.";
    } else {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = $_POST['password'];

        $query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
        
        if (mysqli_num_rows($query) === 1) {
            $user = mysqli_fetch_assoc($query);
            
            if (password_verify($password, $user['password'])) {
                // Reset hitungan jika berhasil login
                $_SESSION['login_attempts'] = 0;

                $_SESSION['id'] = $user['id'];
                $_SESSION['name'] = $user['fullname'];
                $_SESSION['role'] = $user['role'];

                if ($user['role'] === 'admin') {
                    header("Location: ../admin/dashboard_admin.php");
                } else {
                    header("Location: ../supervisor/dashboard_spv.php");
                }
                exit;
            }
        }
        
        // Jika email tidak ada ATAU password salah
        $_SESSION['login_attempts'] += 1;
        $sisa = 3 - $_SESSION['login_attempts'];

        if ($sisa > 0) {
            $error = "Email atau Password salah! Sisa percobaan: " . $sisa;
        } else {
            $error = "Anda telah 3 kali salah. Form login telah di-CLEAR!";
        }
    }
}
?>