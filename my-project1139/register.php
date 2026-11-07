<?php
session_start();
if (isset($_SESSION['login'])) {
    header('Location: index.php');
    exit;
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ElektroShop - Daftar Akun</title>
    <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="signin.css" rel="stylesheet">
    <style>
        body { 
            background: #f0f2f5; 
            color: #1a1a2e;
        }
        .register-box { 
            max-width: 420px; 
            margin: 60px auto; 
            padding: 0 16px; 
        }
        .card { 
            border: none; 
            box-shadow: 0 4px 24px rgba(0,0,0,.15); 
            border-radius: 8px;
            overflow: hidden;
        }
        /* Custom header mengikuti warna navy navbar index */
        .card-custom-header {
            background-color: #1a1a2e !important;
            color: #e2b96f !important;
        }
        /* Warna link dan teks aksen emas */
        .accent-link {
            color: #d4a55a !important;
            text-decoration: none;
            font-weight: 600;
        }
        .accent-link:hover {
            color: #1a1a2e !important;
            text-decoration: underline;
        }
        /* Tombol utama disamakan dengan btn-hero di index */
        .btn-hero {
            background-color: #e2b96f;
            color: #1a1a2e;
            font-weight: 600;
            border: none;
            transition: all 0.2s ease-in-out;
        }
        .btn-hero:hover {
            background-color: #d4a55a;
            color: #1a1a2e;
        }
    </style>
</head>
<body>
<div class="register-box">
    <div class="card">
        <div class="card-header card-custom-header text-center py-4 border-0">
            <div style="font-size:3rem; color: #e2b96f;">🛒</div>
            <h2 class="h4 fw-bold mb-1">Daftar Akun Customer</h2>
            <p class="text-white-50 small mb-0">Sudah punya akun? <a href="login.php" class="accent-link">Login di sini</a></p>
        </div>
        
        <div class="card-body p-4 bg-white">
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
            <?php endif; ?>
            
            <form action="proses_register.php" method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" placeholder="Nama lengkap Anda" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="email@contoh.com" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" minlength="6" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Konfirmasi Password</label>
                    <input type="password" name="konfirmasi" class="form-control" placeholder="Ulangi password" required>
                </div>
                <button type="submit" class="btn btn-hero w-100 py-2 shadow-sm">Daftar Sekarang</button>
            </form>
            
            <div class="text-center mt-4">
                <a href="index.php" class="text-muted small text-decoration-none">&larr; Kembali ke Toko</a>
            </div>
        </div>
    </div>
</div>
<script src="assets/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>