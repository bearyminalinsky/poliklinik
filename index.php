<?php
session_start(); // Mulai sesi

// Cek akses halaman
$restricted_pages = ['dokter', 'pasien', 'periksa'];
if (in_array($_GET['page'] ?? '', $restricted_pages) && !isset($_SESSION['username'])) {
    header('Location: loginuser.php'); // Redirect ke halaman login
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poliklinik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                Sistem Informasi Poliklinik
            </a>
            <button class="navbar-toggler"
                    type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNavDropdown"
                    aria-controls="navbarNavDropdown" aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="index.php">
                            Home
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            Data Master
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="index.php?page=dokter">
                                    Dokter
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="index.php?page=pasien">
                                    Pasien
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=periksa">
                            Periksa
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['username'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="loginuser.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="registrasiuser.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <main role="main" class="container mt-4">
        <?php
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            $allowed_pages = ['dokter', 'pasien', 'periksa']; 

            if (in_array($page, $allowed_pages)) {
                echo "<h2>" . htmlspecialchars(ucwords($page)) . "</h2>";
                include($page . ".php");
            } else {
                echo "<h2>Halaman tidak ditemukan.</h2>";
            }
        } else {
            echo "<h2>Selamat Datang di Sistem Informasi Poliklinik</h2>";
        }
        ?>
    </main>
</body>
</html>
