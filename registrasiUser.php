<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $error = '';

    // Cek apakah username sudah ada
    $checkQuery = "SELECT * FROM user WHERE username = '$username'";
    $checkResult = mysqli_query($mysqli, $checkQuery);
    if (mysqli_num_rows($checkResult) > 0) {
        $error = "Username sudah terdaftar!";
    } elseif ($password !== $confirm_password) {
        $error = "Password dan konfirmasi password tidak cocok!";
    } else {
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Simpan user baru ke database
        $insertQuery = "INSERT INTO user (username, password) VALUES ('$username', '$hashedPassword')";
        if (mysqli_query($mysqli, $insertQuery)) {
            $successMessage = "User berhasil ditambahkan. Silakan login.";
            // Redirect ke halaman login setelah berhasil registrasi
            header('Location: loginuser.php');
            exit();
        } else {
            $error = "Terjadi kesalahan saat mendaftar.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            width: 350px; /* Ukuran sedikit lebih besar */
        }
    </style>
</head>
<body>
<div class="container d-flex justify-content-center align-items-center h-100">
    <div class="card">
        <div class="card-body p-4"> <!-- Padding disesuaikan -->
            <h4 class="card-title text-center">Registrasi</h4>
            <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
            <?php if (isset($successMessage)) { echo "<div class='alert alert-success'>$successMessage</div>"; } ?>
            <form method="POST" action="registrasiuser.php">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control form-control-sm" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control form-control-sm" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                    <input type="password" class="form-control form-control-sm" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Daftar</button> <!-- Tombol lebih besar -->
            </form>
            <p class="text-center mt-2 mb-0">Sudah punya akun? <a href="loginuser.php">Login</a></p>
        </div>
    </div>
</div>
</body>
</html>
