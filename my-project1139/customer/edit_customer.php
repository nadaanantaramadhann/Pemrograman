<?php
include '../layout/header.php';
include '../koneksi.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: view_customer.php');
    exit;
}

$id = (int) $_GET['id'];
$data = mysqli_query($conn, "SELECT * FROM tb_users WHERE id=$id AND role='customer'");
$row = mysqli_fetch_assoc($data);

if (!$row) {
    header('Location: view_customer.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = mysqli_real_escape_string($conn, trim($_POST['nama']));
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    
    if ($nama == '' || $username == '') {
        $error = 'Semua field wajib diisi.';
    } else {
        $cek = mysqli_query($conn, "SELECT id FROM tb_users WHERE username='$username' AND id != $id");
        if (mysqli_num_rows($cek) > 0) {
            $error = 'Username/Email sudah digunakan.';
        } else {
            $sql = "UPDATE tb_users SET nama='$nama', username='$username' WHERE id=$id";
            if (mysqli_query($conn, $sql)) {
                header('Location: view_customer.php?pesan=edit');
                exit;
            } else {
                $error = 'Gagal mengubah data: ' . mysqli_error($conn);
            }
        }
    }
}
?>
<div class="container-fluid">
    <div class="row">
        <?php include '../layout/sidebar.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h2>Edit Customer</h2>
                <a href="view_customer.php" class="btn btn-secondary btn-sm">&larr; Kembali</a>
            </div>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <div class="row">
                <div class="col-md-6">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" name="nama" value="<?= htmlspecialchars($_POST['nama'] ?? $row['nama']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Username / Email</label>
                            <input type="text" class="form-control" name="username" value="<?= htmlspecialchars($_POST['username'] ?? $row['username']) ?>" required>
                        </div>
                        <button type="submit" class="btn btn-warning">Update</button>
                        <a href="view_customer.php" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
<?php include '../layout/footer.php'; ?>