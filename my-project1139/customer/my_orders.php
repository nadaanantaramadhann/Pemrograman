<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['login'])) {
    header('Location: ../login.php');
    exit;
}

$role = $_SESSION['role'] ?? '';
$customer_id = (int) $_SESSION['user_id'];
$nama = $_SESSION['nama'] ?? 'Customer';

// Query dipastikan mengambil seluruh data secara benar
$stmt = mysqli_prepare($conn, 
    "SELECT o.*, p.name_product, p.code_product, p.image 
     FROM tb_orders o 
     JOIN tb_product p ON p.id = o.product_id 
     WHERE o.user_id = ? 
     ORDER BY o.tanggal_order DESC"
);

mysqli_stmt_bind_param($stmt, "i", $customer_id);
mysqli_stmt_execute($stmt);
$orders = mysqli_stmt_get_result($stmt);
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pesanan Saya - ElektroShop</title>
    <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f0f2f5; color: #1a1a2e; }
        
        /* Navbar matching dengan index.php */
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
        .nav-link:hover {
            color: #e2b96f !important;
        }
        
        /* Card & Text styling */
        .card {
            border: 1px solid #e0e4ef !important;
            border-radius: 8px;
        }
        .text-primary {
            color: #0f3460 !important;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="../index.php">ElektroShop</a>
        <div class="d-flex align-items-center ms-auto gap-3">
            <span class="text-white-50 small">
                Halo, <strong class="text-white"><?= htmlspecialchars($nama) ?></strong>
                <span class="badge bg-secondary"><?= htmlspecialchars($role) ?></span>
            </span>
            <a href="../index.php" class="btn btn-outline-light btn-sm">Belanja Lagi</a>
            <a href="../logout.php" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container py-5">
    <h2 class="fw-bold mb-4" style="color: #1a1a2e;">Pesanan Saya</h2>
    
    <?php if (mysqli_num_rows($orders) === 0): ?>
        <div class="text-center py-5 text-muted">
            <div style="font-size:4rem">🛒</div>
            <p class="mt-3">Belum ada pesanan. <a href="../index.php">Belanja sekarang!</a></p>
        </div>
    <?php else: ?>
        <div class="row g-3">
            <?php while ($row = mysqli_fetch_assoc($orders)): 
                $badge = match($row['status']) {
                    'pending' => 'warning',
                    'proses' => 'info',
                    'selesai' => 'success',
                    'batal' => 'danger',
                    default => 'secondary'
                };
                
                // Mengamankan pembacaan tanggal agar tidak bernilai kosong/NULL saat di-render
                $tanggal_mentah = $row['tanggal_order'] ?? '';
                $tanggal_tampil = (!empty($tanggal_mentah) && $tanggal_mentah != '0000-00-00 00:00:00') 
                    ? date('d/m/Y H:i', strtotime($tanggal_mentah)) 
                    : 'Tanggal tidak tersedia';
            ?>
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-start gap-3">
                            <?php if (!empty($row['image']) && file_exists('../image/'. $row['image'])): ?>
                                <img src="../image/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name_product']) ?>" class="rounded border" style="width: 70px; height:70px; object-fit:contain; background-color:#fff; padding:2px;">
                            <?php else: ?>
                                <div class="rounded bg-light d-flex align-items-center justify-content-center border" style="width: 70px; height:70px; font-size:2rem;">📱</div>
                            <?php endif; ?>
                            
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1" style="color: #1a1a2e;"><?= htmlspecialchars($row['name_product']) ?></h6>
                                <p class="text-muted small mb-1">Kode: <?= htmlspecialchars($row['code_product'] ?? '-') ?></p>
                                <span class="badge bg-<?= $badge ?> mb-2"><?= htmlspecialchars($row['status']) ?></span>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="small text-muted">Qty: <?= (int)$row['qty'] ?></span>
                                    <strong class="text-primary">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></strong>
                                </div>
                                
                                <?php if (!empty($row['catatan'])): ?>
                                    <p class="small text-muted mt-2 mb-1" style="background: #eef1f6; padding: 4px 8px; border-radius: 4px;">
                                        <strong>Catatan:</strong> <?= htmlspecialchars($row['catatan']) ?>
                                    </p>
                                <?php endif; ?>
                                
                                <p class="text-muted small mt-2 mb-0" style="font-size: 0.78rem;">
                                    📅 <?= $tanggal_tampil ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</div>

<script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>