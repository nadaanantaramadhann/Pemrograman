<?php
// Memastikan session aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../layout/header.php';
include '../koneksi.php';

// Memastikan variabel $_role tersedia
$_role = $_role ?? ($_SESSION['role'] ?? 'customer');

if (!in_array($_role, ['admin', 'staff'])) {
    header('Location: ../index.php');
    exit;
}

// Mengambil data order dan melakukan join tabel
$orders = mysqli_query($conn, 
    "SELECT o.*, u.nama AS nama_customer, u.username AS email_customer, p.name_product, p.code_product 
     FROM tb_orders o 
     JOIN tb_users u ON u.id = o.user_id 
     JOIN tb_product p ON p.id = o.product_id 
     ORDER BY o.id DESC"
);
?>
<div class="container-fluid">
    <div class="row">
        <?php include '../layout/sidebar.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h2>Data Orders</h2>
            </div>
            
            <?php if (isset($_GET['pesan']) && $_GET['pesan'] === 'update'): ?>
                <div class="alert alert-success alert-dismissible fade show">Status order berhasil diupdate. <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-striped table-sm align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Produk</th>
                            <th>Qty</th>
                            <th>Total</th>
                            <th>Catatan</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; while ($row = mysqli_fetch_assoc($orders)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <strong><?= htmlspecialchars($row['nama_customer'] ?? '-') ?></strong><br>
                                <small class="text-muted"><?= htmlspecialchars($row['email_customer'] ?? '-') ?></small>
                            </td>
                            <td>
                                <?= htmlspecialchars($row['name_product'] ?? '-') ?><br>
                                <small class="text-muted"><?= htmlspecialchars($row['code_product'] ?? '-') ?></small>
                            </td>
                            <td><?= $row['qty'] ?? 0 ?></td>
                            <td>Rp <?= number_format($row['total_harga'] ?? 0, 0, ',', '.') ?></td>
                            <td><?= htmlspecialchars($row['catatan'] ?? '-') ?></td>
                            <td>
                                <?php
                                $status_order = $row['status'] ?? 'pending';
                                $badge = match($status_order) {
                                    'pending' => 'warning',
                                    'proses' => 'info',
                                    'selesai' => 'success',
                                    'batal' => 'danger',
                                    default => 'secondary'
                                };
                                ?>
                                <span class="badge bg-<?= $badge ?>"><?= $status_order ?></span>
                            </td>
                            <td>
                                <?php 
                                // FIX: Mencegah error passing null ke strtotime jika kolom tanggal_order kosong di DB
                                $tanggal = $row['tanggal_order'] ?? $row['created_at'] ?? null;
                                echo $tanggal ? date('d/m/Y H:i', strtotime($tanggal)) : '-';
                                ?>
                            </td>
                            <td>
                                <form action="update_status.php" method="POST" class="d-flex gap-1">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <select name="status" class="form-select form-select-sm" style="min-width: 90px">
                                        <?php foreach (['pending', 'proses', 'selesai', 'batal'] as $s): ?>
                                            <option value="<?= $s ?>" <?= ($row['status'] ?? 'pending') === $s ? 'selected' : '' ?>><?= $s ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button class="btn btn-sm btn-primary">Simpan</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>
<?php include '../layout/footer.php'; ?>