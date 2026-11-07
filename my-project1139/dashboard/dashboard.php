<?php
// TENTUKAN HALAMAN AKTIF UNTUK SINKRONISASI NAVIGASI SIDEBAR/NAVBAR
$page = 'dashboard'; 

include '../layout/header.php';
include '../koneksi.php';

// Statistik
$total_produk = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM tb_product"))[0];
$total_customer = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM tb_users WHERE role='customer'"))[0];
$total_order = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM tb_orders"))[0];
$order_pending = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM tb_orders WHERE status='pending'"))[0];
$stok_menipis = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM tb_product WHERE stock <= min AND min > 0"))[0];
?>
<div class="container-fluid">
    <div class="row">
        <?php include '../layout/sidebar.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-4 border-bottom">
                <h1 class="h2">Dashboard</h1>
                <span class="text-muted">Selamat datang, <strong><?= htmlspecialchars($_nama ?? 'Admin') ?></strong></span>
            </div>
            
            <div class="row g-3 mb-4">
                <div class="col-sm-6 col-lg-3">
                    <div class="card border-0 shadow-sm text-white" style="background-color: #1e293b;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="mb-1 small opacity-75">Total Produk</p>
                                    <h3 class="mb-0 fw-bold"><?= $total_produk ?></h3>
                                </div>
                                <div style="font-size:2.5rem; opacity:.3">🛒</div>
                            </div>
                        </div>
                        <div class="card-footer border-0 py-1" style="background-color: rgba(255,255,255,0.05);">
                            <a href="../product/view_product.php" class="small text-decoration-none text-white-50">Lihat semua &rarr;</a>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="card border-0 shadow-sm text-white" style="background-color: #0f766e;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="mb-1 small opacity-75">Total Customer</p>
                                    <h3 class="mb-0 fw-bold"><?= $total_customer ?></h3>
                                </div>
                                <div style="font-size:2.5rem; opacity:.3">👥</div>
                            </div>
                        </div>
                        <div class="card-footer border-0 py-1" style="background-color: rgba(255,255,255,0.05);">
                            <a href="../customer/view_customer.php" class="small text-decoration-none text-white-50">Lihat semua &rarr;</a>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="card border-0 shadow-sm text-white" style="background-color: #b45309;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="mb-1 small opacity-75">Order Pending</p>
                                    <h3 class="mb-0 fw-bold"><?= $order_pending ?></h3>
                                </div>
                                <div style="font-size:2.5rem; opacity:.3">⏰</div>
                            </div>
                        </div>
                        <div class="card-footer border-0 py-1" style="background-color: rgba(255,255,255,0.05);">
                            <a href="../orders/view_orders.php" class="small text-decoration-none text-white-50">Proses &rarr;</a>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="card border-0 shadow-sm text-white" style="background-color: <?= $stok_menipis > 0 ? '#991b1b' : '#334155' ?>;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="mb-1 small opacity-75">Stok Menipis</p>
                                    <h3 class="mb-0 fw-bold"><?= $stok_menipis ?></h3>
                                </div>
                                <div style="font-size:2.5rem; opacity:.3">⚠️</div>
                            </div>
                        </div>
                        <div class="card-footer border-0 py-1" style="background-color: rgba(255,255,255,0.05);">
                            <a href="../product/view_product.php" class="small text-decoration-none text-white-50">Cek produk &rarr;</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <h5 class="fw-bold mb-3">Order Terbaru</h5>
            <div class="table-responsive">
                <?php
                $recent = mysqli_query($conn, "SELECT * FROM tb_orders ORDER BY tanggal_order DESC LIMIT 10");
                ?>
                <table class="table table-striped table-sm align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Customer</th>
                            <th>Produk</th>
                            <th>Qty</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($recent && mysqli_num_rows($recent) > 0): ?>
                            <?php while ($r = mysqli_fetch_assoc($recent)): 
                                $status_style = match($r['status'] ?? 'pending') {
                                    'pending' => 'background-color: #b45309; color: #ffffff;', 
                                    'proses'   => 'background-color: #1e293b; color: #ffffff;', 
                                    'selesai'  => 'background-color: #0f766e; color: #ffffff;', 
                                    'batal'    => 'background-color: #991b1b; color: #ffffff;', 
                                    default    => 'background-color: #334155; color: #ffffff;'  
                                };

                                // 1. Ambil Nama Customer
                                $cust_id = $r['user_id'] ?? $r['customer_id'] ?? $r['id_user'] ?? null;
                                $nm_customer = "User #" . ($cust_id ?? 'Unknown');
                                if ($cust_id) {
                                    $u_query = mysqli_query($conn, "SELECT nama FROM tb_users WHERE id = '$cust_id'");
                                    if ($u_query && mysqli_num_rows($u_query) > 0) {
                                        $nm_customer = mysqli_fetch_assoc($u_query)['nama'];
                                    }
                                }

                                // 2. Ambil Nama Produk
                                $prod_id = $r['product_id'] ?? $r['id_product'] ?? null;
                                $name_product = "Produk #" . ($prod_id ?? 'Unknown');
                                if ($prod_id) {
                                    $p_query = mysqli_query($conn, "SELECT name_product FROM tb_product WHERE id = '$prod_id'");
                                    if ($p_query && mysqli_num_rows($p_query) > 0) {
                                        $name_product = mysqli_fetch_assoc($p_query)['name_product'];
                                    }
                                }
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($nm_customer) ?></td>
                                <td><?= htmlspecialchars($name_product) ?></td>
                                <td><?= $r['qty'] ?? 0 ?></td>
                                <td>Rp <?= number_format($r['total_harga'] ?? 0, 0, ',', '.') ?></td>
                                <td>
                                    <span class="badge px-2.5 py-1.5 fw-semibold shadow-sm" style="<?= $status_style ?> font-size: 0.75rem; border-radius: 4px; text-transform: uppercase; letter-spacing: 0.5px;">
                                        <?= htmlspecialchars($r['status'] ?? 'pending') ?>
                                    </span>
                                </td>
                                <td><?= isset($r['tanggal_order']) && $r['tanggal_order'] != '-' ? date('d/m/Y H:i', strtotime($r['tanggal_order'])) : '-' ?></td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">Belum ada data order terbaru.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>
<?php include '../layout/footer.php'; ?>