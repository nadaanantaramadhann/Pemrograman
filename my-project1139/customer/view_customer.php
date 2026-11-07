<?php
include '../layout/header.php';
include '../koneksi.php';

if (!in_array($_role, ['admin', 'staff'])) {
    header('Location: ../index.php');
    exit;
}

// SUDAH DIPERBAIKI: Mengubah customer_id menjadi user_id sesuai dengan isi database lu
$data = mysqli_query($conn, 
    "SELECT id, nama, username, 
    (SELECT COUNT(*) FROM tb_orders WHERE user_id = tb_users.id) AS total_order 
     FROM tb_users 
     WHERE role = 'customer' 
     ORDER BY nama ASC"
);
?>
<div class="container-fluid">
    <div class="row">
        <?php include '../layout/sidebar.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h2>Data Customers</h2>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-sm align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Total Order</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; while ($row = mysqli_fetch_assoc($data)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['nama'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td><span class="badge bg-primary"><?= $row['total_order'] ?> order</span></td>
                            <td>
                                <?php if ($_role === 'admin'): ?>
                                    <a href="../admin/edit_user.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="../admin/delete_user.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus customer ini?')">Hapus</a>
                                <?php else: ?>
                                    <span class="text-muted small">-</span>
                                <?php endif; ?>
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