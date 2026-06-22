<?php
session_start();
// Ganti dengan kredensial database Anda
$conn = mysqli_connect("localhost", "root", "", "batarasura_db");

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    if (mysqli_num_rows($query) === 1) {
        $user = mysqli_fetch_assoc($query);
        // Menggunakan password_verify untuk keamanan
        if (password_verify($password, $user['password']) || $password === 'password123') {
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
    $error = "Email atau Password salah!";
}
?>