<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// OTOMATIS MENDETEKSI LOKASI FILE INCLUDE (Biar ga error include path)
$header_path = file_exists('../layout/header.php') ? '../layout/header.php' : (file_exists('layout/header.php') ? 'layout/header.php' : '../../layout/header.php');
$koneksi_path = file_exists('../koneksi.php') ? '../koneksi.php' : (file_exists('koneksi.php') ? 'koneksi.php' : '../../koneksi.php');
$sidebar_path = file_exists('../layout/sidebar.php') ? '../layout/sidebar.php' : (file_exists('layout/sidebar.php') ? 'layout/sidebar.php' : '../../layout/sidebar.php');
$footer_path = file_exists('../layout/footer.php') ? '../layout/footer.php' : (file_exists('layout/footer.php') ? 'layout/footer.php' : '../../layout/footer.php');

include_once $header_path;
include_once $koneksi_path;

// Memastikan variabel $_role tersedia dari session jika header belum membuatnya
$_role = $_role ?? ($_SESSION['role'] ?? 'customer');

if ($_role !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$users = mysqli_query($conn, "SELECT * FROM tb_users ORDER BY role, nama ASC");
?>
<div class="container-fluid">
    <div class="row">
        <?php include $sidebar_path; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h2>Kelola User & Hak Akses</h2>
                <a href="insert_user.php" class="btn btn-primary btn-sm">+ Tambah User</a>
            </div>
            
            <?php if (isset($_GET['pesan'])): ?>
                <?php if ($_GET['pesan'] === 'tambah'): ?>
                    <div class="alert alert-success alert-dismissible fade show">User berhasil ditambahkan. <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                <?php elseif ($_GET['pesan'] === 'edit'): ?>
                    <div class="alert alert-success alert-dismissible fade show">User berhasil diupdate. <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                <?php elseif ($_GET['pesan'] === 'hapus'): ?>
                    <div class="alert alert-success alert-dismissible fade show">User berhasil dihapus. <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-striped table-sm align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Email (Username)</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; while ($row = mysqli_fetch_assoc($users)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['nama'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td>
                                <?php
                                $rb = match($row['role'] ?? 'customer') {
                                    'admin' => 'danger',
                                    'staff' => 'warning',
                                    'customer' => 'primary',
                                    default => 'secondary'
                                };
                                ?>
                                <span class="badge bg-<?= $rb ?>"><?= $row['role'] ?? 'customer' ?></span>
                            </td>
                            <td>
                                <a href="edit_user.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                <?php if (isset($_SESSION['user_id']) && $row['id'] != $_SESSION['user_id']): ?>
                                    <a href="delete_user.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus user ini?')">Hapus</a>
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
<?php include_once $footer_path; ?>