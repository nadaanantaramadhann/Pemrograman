<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['login'])) {
    header("Location: ../index.php");
    exit;
}
$_role = $_SESSION['role'] ?? 'customer';
$_nama = $_SESSION['nama'] ?? 'User';
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - ElektroShop</title>
    <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/dashboard.css" rel="stylesheet">
    <style>
        .bd-placeholder-img { font-size: 1.125rem; text-anchor: middle; -webkit-user-select:none; user-select:none; }
        @media (min-width: 768px) { .bd-placeholder-img-lg { font-size: 3.5rem; } }
    </style>
</head>
<body>
<header class="navbar navbar-dark sticky-top flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="../index.php"> ElektroShop</a>
    <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false">
        <span class="navbar-toggler-icon"></span>
    </button>
    <input class="form-control form-control-dark w-100" type="text" placeholder="Cari produk, order, pelanggan..." aria-label="Search">
    <div class="navbar-nav">
        <div class="nav-item text-nowrap d-flex align-items-center gap-2 px-3">
            <span class="text-white-50 small">
                <?= htmlspecialchars($_nama) ?>
                <span class="badge bg-secondary"><?= $_role ?></span>
            </span>
            <a class="nav-link" href="../logout.php" style="color:#e2b96f">Keluar</a>
        </div>
    </div>
</header>