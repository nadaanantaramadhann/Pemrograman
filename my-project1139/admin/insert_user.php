<?php
include '../layout/header.php';
include '../koneksi.php';

if ($_role !== 'admin') { header('Location: ../index.php'); exit; }
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($conn, trim($_POST['nama']));
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = $_POST['password'];
    $role = in_array($_POST['role'], ['admin', 'staff', 'customer']) ? $_POST['role'] : 'customer';
    
    if (!$nama || !$username || !$email || !$password) {
        $error = 'Semua field wajib diisi.';
    } else {
        $cek = mysqli_query($conn, "SELECT id FROM tb_users WHERE email='$email' OR username='$username'");
        if (mysqli_num_rows($cek) > 0) {
            $error = 'Email atau username sudah terdaftar.';
        } else {
            $pass_hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO tb_users (nama, username, email, password, role) VALUES ('$nama', '$username', '$email', '$pass_hash', '$role')";
            if (mysqli_query($conn, $sql)) {
                header('Location: view_users.php?pesan=tambah');
                exit;
            } else {
                $error = 'Gagal: ' . mysqli_error($conn);
            }
        }
    }
}
?>
<div class="container-fluid">
    <div class="row">
        <?php include '../layout/sidebar.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h2>Tambah User</h2>
                <a href="view_users.php" class="btn btn-secondary btn-sm">&larr; Kembali</a>
            </div>
            <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
            <div class="col-md-6">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" minlength="6" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select">
                            <option value="customer">Customer</option>
                            <option value="staff">Staff</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="view_users.php" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </main>
    </div>
</div>
<?php include '../layout/footer.php'; ?>