<?php
session_start();
include 'koneksi.php';

// FIX: Mendefinisikan variabel agar tidak memicu Undefined Variable error
$error = $_SESSION['error'] ?? '';
$lastVisit = $_COOKIE['last_visit'] ?? 'Pertama kali';

unset($_SESSION['error']);

$is_logged_in = isset($_SESSION['login']) && $_SESSION['login'] === true;
$role = $_SESSION['role'] ?? '';
$nama = $_SESSION['nama'] ?? '';
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ElektroShop - Tentang Kami</title>
    <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
            color: #1a1a2e;
        }

        /* navbar (Sama persis dengan index.php) */
        .navbar {
            background-color: #1a1a2e !important;
            border-bottom: 1px solid #16213e;
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.3rem;
            color: #e2b96f !important;
        }

        .nav-link {
            color: #c8c8d4 !important;
        }

        .nav-link:hover, .nav-link.active {
            color: #e2b96f !important;
        }

        /* Hero button style buat tombol daftar & tombol proses nilai */
        .btn-hero {
            background-color: #e2b96f;
            color: #1a1a2e;
            font-weight: 600;
            border: none;
        }

        .btn-hero:hover {
            background-color: #d4a55a;
            color: #1a1a2e;
        }

        /* custom style untuk card tabel input mahasiswa */
        .card-custom-header {
            background-color: #1a1a2e !important;
            color: #e2b96f !important;
        }

        /* footer */
        footer {
            background-color: #1a1a2e;
            color: #a8b2d8;
            padding: 24px 0;
            margin-top: 60px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">ElektroShop</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" style="border-color:#e2b96f">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php#produk">Produk</a></li>
                <li class="nav-item"><a class="nav-link active" href="about_us.php">Tentang Kami</a></li>
            </ul>
            <div class="d-flex gap-2 align-items-center">
                <?php if ($is_logged_in): ?>
                    <span class="text-white-50 small">
                        Halo, <strong class="text-white"><?= htmlspecialchars($nama) ?></strong>
                        <span class="badge bg-secondary"><?= $role ?></span>
                    </span>
                    <?php if ($role === 'admin' || $role === 'staff'): ?>
                        <a href="dashboard/dashboard.php" class="btn btn-outline-light btn-sm">Dashboard</a>
                    <?php else: ?>
                        <a href="customer/my_orders.php" class="btn btn-outline-light btn-sm">Pesanan Saya</a>
                    <?php endif; ?>
                    <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline-light btn-sm">Login</a>
                    <a href="register.php" class="btn btn-sm btn-hero">Daftar</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<div class="container my-5">
    <div class="card p-4 shadow-sm border-0 mb-4">
        <?php 
            $harga_barang = 210000;
            if ($harga_barang > 100000 && $harga_barang < 200000) {
                 echo "<p class='alert alert-success m-0'>dapat diskon</p>"; 
            } else if ($harga_barang >= 200000) {
                 echo "<p class='alert alert-danger m-0'>Harga barang mahal</p>";
            } else {
                 echo "<p class='alert alert-info m-0'>dapat diskon 20%</p>";
            }
        ?>
    </div>
</div>

<table border="2" cellpadding="15" align="center" style="border-collapse: separate" cellspacing="15">
    <?php
    for ($i=1; $i <= 5; $i++){
        echo "<tr>";
        for ($j=0; $j <= 5; $j++){
            echo "<td align='center' style='border:2px solid black' >$i,$j</td>";
        }
        echo "</tr>";
    }
    ?>
</table>

<div class="container text-center my-4">
    <?php
        function sayHello() {
            echo "<h2> ini adalah function pertama saya di PHP </h2>";
        }    
        echo "<h1> Function</h1>"; 
        sayHello();

        function panggilAngka(){
            $x = 10;
            echo "<p>X adalah variable local : $x</p>";
        }
        panggilAngka();
        
        $x = 11;
        function panggilAngka1(){
            $x = 11; 
            echo "<p>X adalah variable global : $x</p>";
        }
        panggilAngka1();

        function angkaHitung() {
            static $nilai = 0;
            $nilai++;
            echo "<p>Panggil nilai ke-$nilai</p>";
        }
        angkaHitung();
        angkaHitung();

        function tambah($a, $b){
            return $a + $b;
        }
        $hasil=tambah(5,4);
        echo "<p class='fw-bold text-primary fs-5'>Hasil tambah: $hasil</p>";
    ?>
</div>

<div class="container mt-5 mb-5">
    <div class="card shadow-lg border-0">
        <div class="card-header card-custom-header">
            <h4 class="mb-0 fw-bold">Input Nilai Mahasiswa</h4>
            <?php if(isset($_SESSION['nama'])): ?>
                <small class="text-white-50">Halo, <?= htmlspecialchars($_SESSION['nama']); ?></small><br>
            <?php endif; ?>
            <small class="text-white-50">Terakhir akses: <?= htmlspecialchars($lastVisit); ?></small>
        </div>

        <div class="card-body">
            <?php if($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error); ?> </div>
            <?php endif; ?>
            
            <form method="POST" action="proses.php">
                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label>Nilai 1</label>
                        <input type="number" name="nilai[]" class="form-control" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Nilai 2</label>
                        <input type="number" name="nilai[]" class="form-control" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Nilai 3</label>
                        <input type="number" name="nilai[]" class="form-control" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Nilai 4</label>
                        <input type="number" name="nilai[]" class="form-control" required>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" class="form-control" name="email">
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" class="form-control" name="pwd">
                </div>
                <div class="mb-3">
                    <label>Jenis Kelamin</label>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" name="gender" value="Laki-laki">
                        <label class="form-check-label">Laki-laki</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" class="form-check-input" name="gender" value="Perempuan">
                        <label class="form-check-label">Perempuan</label>
                    </div>
                </div>
                <div class="mb-3">
                    <label>Hobi</label>
                    <div class="form-check">
                        <input type="checkbox" name="hobi[]" value="Shopping" class="form-check-input">
                        <label class="form-check-label">Shopping</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="hobi[]" value="Nonton Film" class="form-check-input">
                        <label class="form-check-label">Nonton Film</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="hobi[]" value="Main Game" class="form-check-input">
                        <label class="form-check-label">Main Game</label>
                    </div>
                </div>
                <button type="submit" name="submit" class="btn btn-hero px-4 shadow-sm">Proses Nilai</button>
            </form>
        </div>
    </div>
</div>

<footer class="text-center">
    <div class="container">
        <p class="mb-0">&copy; <?= date('Y') ?> ElektroShop. All rights reserved.</p>
    </div>
</footer>

<script src="assets/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>