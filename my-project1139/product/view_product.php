<?php
include '../layout/header.php';
include '../koneksi.php';
$data = mysqli_query($conn, "SELECT * FROM tb_product ORDER BY name_product ASC");
?>
<div class="container-fluid">
    <div class="row">
        <?php include '../layout/sidebar.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h2>Data Product</h2>
                <div class="d-flex gap-2">
                    <a href="export_excel.php" class="btn btn-success btn-sm">&#128196; Export Excel</a>
                    <a href="export_pdf.php" class="btn btn-danger btn-sm">&#128438; Export PDF</a>
                    <a href="insert_product.php" class="btn btn-primary btn-sm">+ Tambah Product</a>
                </div>
            </div>
            
            <?php if (isset($_GET['pesan'])): ?>
                <?php if ($_GET['pesan'] == 'tambah'): ?>
                    <div class="alert alert-success alert-dismissible fade show">Data product berhasil ditambahkan. <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                <?php elseif ($_GET['pesan'] == 'edit'): ?>
                    <div class="alert alert-success alert-dismissible fade show">Data product berhasil diubah. <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                <?php elseif ($_GET['pesan'] == 'hapus'): ?>
                    <div class="alert alert-success alert-dismissible fade show">Data product berhasil dihapus. <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                <?php elseif ($_GET['pesan'] == 'error'): ?>
                    <div class="alert alert-danger alert-dismissible fade show">Gagal menghapus data. <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-striped table-sm align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Gambar</th>
                            <th>Code Product</th>
                            <th>Name Product</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Min</th>
                            <th>Max</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; while ($row = mysqli_fetch_assoc($data)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <?php if (!empty($row['image']) && file_exists('../image/'.$row['image'])): ?>
                                    <img src="../image/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name_product']) ?>" class="img-thumbnail" style="width:60px; height:60px; object-fit:contain; background-color:#fff; padding:2px;">
                                <?php else: ?>
                                    <span class="text-muted" style="font-size:1.8rem">&#128247;</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($row['code_product']) ?></td>
                            <td><?= htmlspecialchars($row['name_product']) ?></td>
                            <td>Rp <?= number_format($row['price'], 0, ',', '.') ?></td>
                            <td>
                                <?php $stok_class = $row['stock'] <= $row['min'] ? 'text-danger fw-bold' : ''; ?>
                                <span class="<?= $stok_class ?>"><?= $row['stock'] ?></span>
                            </td>
                            <td><?= $row['min'] ?></td>
                            <td><?= $row['max'] ?></td>
                            <td>
                                <a href="edit_product.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="delete_product.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</a>
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