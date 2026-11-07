<?php
session_start();
// include 'koneksi.php';  // uncomment kalau udah ada database

// data produk sementara (nanti bisa diganti dari database)
include 'koneksi.php';
$result = mysqli_query($conn, "SELECT * FROM tb_product ORDER BY name_product ASC");
$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}

$is_logged_in = isset($_SESSION['login']) && $_SESSION['login'] === true;
$role = $_SESSION['role'] ?? '';
$nama = $_SESSION['nama'] ?? '';
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ElektroShop - Toko Elektronik</title>
    <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
            color: #1a1a2e;
        }

        /* navbar */
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

        /* hero */
        .hero-section {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 60%, #0f3460 100%);
            color: white;
            padding: 60px 0;
        }

        .hero-section h1 {
            font-size: 2.2rem;
            font-weight: bold;
            color: #f5f5f5;
        }

        .hero-section p {
            color: #a8b2d8;
        }

        .btn-hero {
            background-color: #e2b96f;
            color: #1a1a2e;
            font-weight: 600;
            border: none;
            padding: 10px 30px;
        }

        .btn-hero:hover {
            background-color: #d4a55a;
            color: #1a1a2e;
        }

        /* produk */
        .section-produk {
            padding: 50px 0;
        }

        .section-title {
            font-weight: 700;
            color: #1a1a2e;
        }

        .card-produk {
            transition: 0.25s;
            height: 100%;
            border: 1px solid #e0e4ef !important;
            background-color: #ffffff;
        }

        .card-produk:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(26, 26, 46, 0.12) !important;
            border-color: #e2b96f !important;
        }

        .img-produk {
            height: 180px;
            background: linear-gradient(135deg, #f0f2f5, #e4e8f0);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            overflow: hidden; /* Mencegah gambar keluar dari kotak pembungkus */
        }

       .img-produk img {
            width: 100%;
            height: 100%;
            object-fit: contain; /* <--- Diubah jadi contain agar gambar utuh mengecil */
            padding: 10px;       /* Opsional: memberi jarak sedikit agar gambar tidak menempel ke tepi kotak */
        }

        .harga {
            color: #0f3460;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .badge-stok-ok {
            background-color: #d4edda;
            color: #155724;
            font-weight: 500;
        }

        .badge-stok-habis {
            background-color: #f8d7da;
            color: #721c24;
            font-weight: 500;
        }

        .kode-produk {
            color: #888;
            font-size: 0.78rem;
        }

        /* tombol */
        .btn-pesan {
            background-color: #0f3460;
            color: #fff;
            border: none;
            font-size: 0.85rem;
        }

        .btn-pesan:hover {
            background-color: #e2b96f;
            color: #1a1a2e;
        }

        .btn-login-pesan {
            border: 1px solid #0f3460;
            color: #0f3460;
            font-size: 0.85rem;
            background: transparent;
        }

        .btn-login-pesan:hover {
            background-color: #0f3460;
            color: white;
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
                <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#produk">Produk</a></li>
                <li class="nav-item"><a class="nav-link" href="about_us.php">Tentang Kami</a></li>
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

<div class="hero-section text-center">
    <div class="container">
        <h1>Selamat Datang di ElektroShop</h1>
        <p class="lead mb-4">Toko elektronik dan gadget terpercaya, harga bersaing!</p>
        <a href="#produk" class="btn btn-hero btn-lg">Lihat Produk</a>
    </div>
</div>

<div class="section-produk" id="produk">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="section-title mb-0">Katalog Produk</h4>
            <small class="text-muted"><?= count($products) ?> produk tersedia</small>
        </div>

        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'login_required'): ?>
            <div class="alert alert-warning alert-dismissible fade show">
                Silakan <a href="login.php">login</a> atau <a href="register.php">daftar</a> dulu untuk pesan.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'order_ok'): ?>
            <div class="alert alert-success alert-dismissible fade show">
                Pesanan berhasil! Cek <a href="customer/my_orders.php">Pesanan Saya</a>.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            <?php
            // Emoji fallback jika database kolom image bernilai kosong/NULL
            $icons = ['📱', '💻', '🎧', '📟', '🔌', '🖱️', '⌚', '📷'];
            foreach ($products as $i => $row):
                $stok_ok = $row['stock'] > 0;
                $icon = $icons[$i % count($icons)];
            ?>
            <div class="col">
                <div class="card card-produk border-0 shadow-sm">
                    <div class="img-produk">
                        <?php if (!empty($row['image'])): ?>
                            <img src="image/<?= $row['image'] ?>" alt="<?= htmlspecialchars($row['name_product']) ?>">
                        <?php else: ?>
                            <?= $icon ?>
                        <?php endif; ?>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <span class="badge mb-2 <?= $stok_ok ? 'badge-stok-ok' : 'badge-stok-habis' ?>" style="width:fit-content">
                            <?= $stok_ok ? 'Stok: ' . $row['stock'] : 'Habis' ?>
                        </span>
                        <h6 class="card-title fw-bold mb-1"><?= htmlspecialchars($row['name_product']) ?></h6>
                        <small class="kode-produk mb-2">Kode: <?= htmlspecialchars($row['code_product']) ?></small>
                        <p class="harga mb-3">Rp <?= number_format($row['price'], 0, ',', '.') ?></p>

                        <?php if ($stok_ok): ?>
                            <?php if ($is_logged_in && $role === 'customer'): ?>
                                <button class="btn btn-pesan btn-sm mt-auto"
                                    data-bs-toggle="modal" data-bs-target="#modalOrder"
                                    data-id="<?= $row['id'] ?>"
                                    data-nama="<?= htmlspecialchars($row['name_product']) ?>"
                                    data-harga="<?= $row['price'] ?>"
                                    data-stok="<?= $row['stock'] ?>">
                                    🛒 Pesan Sekarang
                                </button>
                            <?php elseif ($is_logged_in): ?>
                                <button class="btn btn-outline-secondary btn-sm mt-auto" disabled>
                                    Login sebagai customer untuk pesan
                                </button>
                            <?php else: ?>
                                <a href="login.php" class="btn btn-login-pesan btn-sm mt-auto">
                                    Login untuk Pesan
                                </a>
                            <?php endif; ?>
                        <?php else: ?>
                            <button class="btn btn-secondary btn-sm mt-auto" disabled>Stok Habis</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php if ($is_logged_in && $role === 'customer'): ?>
<div class="modal fade" id="modalOrder" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="customer/proses_order.php" method="POST">
                <div class="modal-header" style="background-color:#1a1a2e; color:white;">
                    <h5 class="modal-title">Buat Pesanan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="product_id" id="order_product_id">
                    <p class="mb-1">Produk: <strong id="order_nama"></strong></p>
                    <p class="mb-3">Harga: <strong id="order_harga_text" style="color:#0f3460"></strong></p>
                    <div class="mb-3">
                        <label class="form-label">Jumlah</label>
                        <input type="number" name="qty" id="order_qty" class="form-control" value="1" min="1" required>
                        <div class="form-text">Stok tersedia: <span id="order_stok"></span></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan (opsional)</label>
                        <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan pesanan..."></textarea>
                    </div>
                    <div class="alert alert-info py-2 mb-0">
                        Total: <strong id="order_total" class="fs-5"></strong>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-pesan">Konfirmasi Pesanan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<footer class="text-center">
    <div class="container">
        <p class="mb-0">&copy; <?= date('Y') ?> ElektroShop. All rights reserved.</p>
    </div>
</footer>

<script src="assets/dist/js/bootstrap.bundle.min.js"></script>
<script>
const modalOrder = document.getElementById('modalOrder');
if (modalOrder) {
    modalOrder.addEventListener('show.bs.modal', function(e) {
        const btn = e.relatedTarget;
        const harga = parseInt(btn.dataset.harga);
        document.getElementById('order_product_id').value = btn.dataset.id;
        document.getElementById('order_nama').textContent = btn.dataset.nama;
        document.getElementById('order_stok').textContent = btn.dataset.stok;
        document.getElementById('order_harga_text').textContent = 'Rp ' + harga.toLocaleString('id-ID');
        document.getElementById('order_qty').max = btn.dataset.stok;
        document.getElementById('order_qty').value = 1;
        document.getElementById('order_total').textContent = 'Rp ' + harga.toLocaleString('id-ID');

        document.getElementById('order_qty').addEventListener('input', function() {
            const qty = parseInt(this.value) || 1;
            document.getElementById('order_total').textContent = 'Rp ' + (qty * harga).toLocaleString('id-ID');
        });
    });
}
</script>
</body>
</html>