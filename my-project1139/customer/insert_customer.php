<?php
include '../layout/header.php';
include '../koneksi.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = mysqli_real_escape_string($conn, trim($_POST['nama']));
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = $_POST['password'];
    
    if ($nama == '' || $username == '' || $password == '') {
        $error = 'Semua field wajib diisi.';
    } else {
        $cek = mysqli_query($conn, "SELECT id FROM tb_users WHERE username='$username'");
        if (mysqli_num_rows($cek) > 0) {
            $error = 'Username / Email sudah terdaftar.';
        } else {
            $pass_hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO tb_users (nama, username, password, role) VALUES ('$nama', '$username', '$pass_hash', 'customer')";
            if (mysqli_query($conn, $sql)) {
                header('Location: view_customer.php?pesan=tambah');
                exit;
            } else {
                $error = 'Gagal menyimpan data: ' . mysqli_error($conn);
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
                <h2>Tambah Customer</h2>
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
                            <input type="text" class="form-control" name="nama" value="<?= isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : '' ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Username / Email</label>
                            <input type="text" class="form-control" name="username" value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="view_customer.php" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
<?php include '../layout/footer.php'; ?>